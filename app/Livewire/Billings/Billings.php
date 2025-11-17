<?php

namespace App\Livewire\Billings;

use Livewire\Component;
use App\Models\Subscriber;
use App\Models\Subscription;
use Carbon\Carbon;
use Hashids\Hashids;
use Barryvdh\DomPDF\Facade\Pdf;

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
    public $totalDiscount = 0;
    public $month_cover_from;
    public $month_cover_to;
    public $billingSummary = [];

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

        if($this->subscriptionHash == "")
        {
            $selectedSubscription = null;
        }
        else
        {
            if (!empty($decoded))
            {
                $subscriptionId = $decoded[0];
                $this->selectedSubscription = Subscription::with(['plan', 'payments'])->findOrFail($subscriptionId);

                // Default month cover range: start month -> current month
                $this->month_cover_from = Carbon::parse($this->selectedSubscription->start_date)->format('Y-m');
                $this->month_cover_to = now()->format('Y-m');

                $this->filterPayments();
            }
        }

        
    }

    public function filterPayments()
    {
        if (!$this->selectedSubscription) return;

        $from = $this->month_cover_from 
            ? Carbon::parse($this->month_cover_from . '-01')->startOfMonth() 
            : Carbon::parse($this->selectedSubscription->start_date)->startOfMonth();

        $to = $this->month_cover_to 
            ? Carbon::parse($this->month_cover_to . '-01')->endOfMonth() 
            : now()->endOfMonth();

        $this->calculateTotals($from, $to);
        $this->generateBillingSummary($from, $to);
    }

    protected function calculateTotals($from, $to)
    {
        if (!$this->selectedSubscription || !$this->selectedSubscription->plan) {
            $this->expectedTotal = $this->totalPaid = 0;
            return;
        }

        $subscription = $this->selectedSubscription;
        $planPrice = abs((float) ($subscription->plan->price ?? 0));

        $billingStart = $from->copy();
        $totalExpected = 0;
        $totalPaid = 0;
        $totalDiscount = 0;

        while ($billingStart->lessThanOrEqualTo($to)) {
            $monthCover = $billingStart->format('Y-m');

            // First month prorate if subscription starts mid-month
            $subscriptionStart = Carbon::parse($subscription->start_date);

            if ($billingStart->format('Y-m') === $subscriptionStart->format('Y-m') && $subscriptionStart->day !== 1) {
                // First billing: from start date to 7th of next month
                $firstBillingEnd = $subscriptionStart->copy()->addMonthNoOverflow()->day(1);
                $daysUsed = $subscriptionStart->diffInDays($firstBillingEnd); // inclusive
                $totalDaysInPeriod = $subscriptionStart->diffInDays($firstBillingEnd) + 1;

                $expectedAmount = ceil(($planPrice / $subscriptionStart->daysInMonth) * $daysUsed);
            } else {
                // Full month
                $expectedAmount = $planPrice;
            }

            // Total paid in this month
            $paidAmount = $subscription->payments
                ->where('month_year_cover', $monthCover)
                ->where('status', 'Approved')
                ->sum('paid_amount');

            $discountAmount = $subscription->payments
                ->where('month_year_cover', $monthCover)
                ->where('status', 'Approved')
                ->sum('discount_amount');

            $totalExpected += $expectedAmount;
            $totalPaid += $paidAmount;
            $totalDiscount += $discountAmount;

            $billingStart->addMonth();
        }

        $this->expectedTotal = $totalExpected;
        $this->totalPaid = $totalPaid;
        $this->totalDiscount = $totalDiscount;
    }

    protected function generateBillingSummary($from, $to)
    {
        if (!$this->selectedSubscription || !$this->selectedSubscription->plan) {
            $this->billingSummary = collect();
            return;
        }

        $subscription = $this->selectedSubscription;
        $planPrice = abs((float) ($subscription->plan->price ?? 0));
        $billingSummary = collect();
        $billingStart = $from->copy();

        while ($billingStart->lessThanOrEqualTo($to)) {
            $monthCover = $billingStart->format('Y-m');

            // First month prorated
            $subscriptionStart = Carbon::parse($subscription->start_date);
            if ($billingStart->format('Y-m') === $subscriptionStart->format('Y-m')
                && $subscriptionStart->day !== 1) {
                $daysInMonth = $billingStart->daysInMonth;
                $daysUsed = $daysInMonth - $subscriptionStart->day + 1;
                $expectedAmount = ceil(($planPrice / $daysInMonth) * $daysUsed);
            } else {
                $expectedAmount = $planPrice;
            }

            $paidAmount = $subscription->payments
                ->where('status', 'Approved')
                ->filter(function($p) use ($monthCover) {
                    return Carbon::parse($p->month_year_cover . '-01')->format('Y-m') === $monthCover;
                })
                ->sum('paid_amount');
            $discountAmount = $subscription->payments
                ->where('status', 'Approved')
                ->filter(function($p) use ($monthCover) {
                    return Carbon::parse($p->month_year_cover . '-01')->format('Y-m') === $monthCover;
                })
                ->sum('discount_amount');
            
            $remaining = max($expectedAmount - $paidAmount, 0);
            
            $status = $remaining - $discountAmount == 0 ? 'Paid' : 'Not Paid';

            $billingSummary->push([
                'month' => $billingStart->format('F Y'),
                'expected_amount' => $expectedAmount,
                'paid_amount' => $paidAmount,
                'status' => $status,
                'discount_amount' => $discountAmount,
                'remaining_balance' => $remaining,
            ]);

            $billingStart->addMonth();
        }

        $this->billingSummary = $billingSummary;
        // dd($billingSummary);
    }


    protected function utf8ize($mixed)
    {
        if (is_array($mixed) || $mixed instanceof \Illuminate\Support\Collection) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
            return $mixed instanceof \Illuminate\Support\Collection ? collect($mixed) : $mixed;
        } elseif (is_string($mixed)) {
            // Force UTF-8
            $mixed = mb_convert_encoding($mixed, 'UTF-8', 'UTF-8');
            // Remove invalid control characters
            $mixed = preg_replace('/[^\P{C}\n\t]+/u', '', $mixed);
            return $mixed;
        }
        return $mixed;
    }

    public function generatePdf()
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $subscriptionHash = $hashids->encode($this->selectedSubscription->id);
        return redirect()->route('pdf.billing', [
            'subscriptionHash' => $subscriptionHash,
            'month_cover_from' => $this->month_cover_from,
            'month_cover_to' => $this->month_cover_to,
        ]);
    }

    public function render()
    {
        return view('livewire.billings.billings', [
            'expectedTotal' => $this->expectedTotal,
            'totalPaid' => $this->totalPaid,
        ]);
    }
}
