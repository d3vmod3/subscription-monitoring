<?php

namespace App\Livewire\Billings;

use Livewire\Component;
use App\Models\Subscriber;
use App\Models\Subscription;
use Carbon\Carbon;
use Hashids\Hashids;

class Billings extends Component
{
    public $hash;
    public $subscriberId;
    public $subscriber;
    public $subscriptions = [];
    public $subscriptionHash;
    public $selectedSubscription = null;
    public $payments = null;
    public $year;
    public $expectedTotal = 0;
    public $totalPaid = 0;
    public $date_cover_from;
    public $date_cover_to;

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);

        abort_if(empty($decoded), 404, 'Invalid Subscriber');

        $this->subscriberId = $decoded[0];
        $this->hash = $hash;

        $this->subscriber = Subscriber::with('subscriptions.plan', 'subscriptions.payments')
            ->findOrFail($this->subscriberId);

        $this->subscriptions = $this->subscriber->subscriptions;
        $this->year = now()->year;
    }

    public function updatedSubscriptionHash($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);

        if (!empty($decoded)) {
            $subscriptionId = $decoded[0];
            $this->selectedSubscription = Subscription::with(['plan', 'payments'])->findOrFail($subscriptionId);

            $this->date_cover_from = Carbon::parse($this->selectedSubscription->start_date)->format('Y-m-d');
            $this->date_cover_to = now()->endOfYear()->format('Y-m-d');

            $this->filterPayments();
        }
    }

    public function filterPayments()
    {
        if (!$this->selectedSubscription) return;

        $from = $this->date_cover_from ? Carbon::parse($this->date_cover_from) : now()->startOfYear();
        $to = $this->date_cover_to ? Carbon::parse($this->date_cover_to) : now()->endOfYear();

        $this->payments = $this->selectedSubscription->payments
            ->filter(fn($p) =>
                Carbon::parse($p->date_cover_from)->between($from, $to)
            )
            ->sortBy('date_cover_from');

        $this->calculateTotals();
    }

    protected function calculateTotals()
    {
        if (!$this->selectedSubscription || !$this->selectedSubscription->plan) {
            $this->expectedTotal = $this->totalPaid = 0;
            return;
        }

        $subscription = $this->selectedSubscription;
        $planPrice = abs((float) ($subscription->plan->price ?? 0));

        if ($planPrice == 0) {
            $this->expectedTotal = $this->totalPaid = 0;
            return;
        }

        $from = $this->date_cover_from
            ? Carbon::parse($this->date_cover_from)
            : Carbon::parse($subscription->start_date);

        $to = $this->date_cover_to
            ? Carbon::parse($this->date_cover_to)
            : now()->endOfYear();

        $subscriptionStart = Carbon::parse($subscription->start_date);
        $dueDay = $subscription->due_day;

        // Start from later of subscriptionStart or filter start
        $billingStart = $subscriptionStart->greaterThan($from) ? $subscriptionStart->copy() : $from->copy();

        $total = 0;
        $firstBilling = true;

        while ($billingStart <= $to) {
            // Next billing end = billingStart + 1 month, set to dueDay
            $billingEnd = $billingStart->copy()->addMonth()->day($dueDay);

            // Make sure billingEnd does not exceed filter 'to'
            if ($billingEnd->greaterThan($to)) {
                $billingEnd = $to->copy();
            }

            // Skip billing if billingEnd < billingStart (edge case)
            if ($billingEnd->lessThan($billingStart)) {
                break;
            }

            if ($firstBilling) {
                // Prorate first billing
                $daysUsed = $billingEnd->diffInDays($billingStart) + 1;
                $total += ceil(($planPrice / 30) * $daysUsed);
                $firstBilling = false;
            } else {
                // Full month
                $total += $planPrice;
            }

            // Move to next billing period
            $billingStart = $billingEnd->copy()->addDay();
        }

        $this->expectedTotal = ABS($total);

        // Total paid within the period
        $this->totalPaid = $subscription->payments
            ->filter(fn($payment) =>
                Carbon::parse($payment->date_cover_from)->between($from, $to)
                && $payment->status === 'Approved'
            )
            ->sum('amount');
    }


    public function render()
    {
        return view('livewire.billings.billings', [
            'expectedTotal' => $this->expectedTotal,
            'totalPaid' => $this->totalPaid,
        ]);
    }
}
