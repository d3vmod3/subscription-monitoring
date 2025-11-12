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
    public $date_cover_from;
    public $date_cover_to;

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);

        abort_if(empty($decoded), 404, 'Invalid Subscriber');

        $this->subscriberId = $decoded[0];
        $this->hash = $hash;

        $this->subscriber = Subscriber::with('subscriptions.payments')->findOrFail($this->subscriberId);

        // Get this subscriber's subscriptions
        $this->subscriptions = $this->subscriber->subscriptions;

        $this->year = now()->year;
    }

    public function updatedSubscriptionHash($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);

        if (!empty($decoded)) {
            $subscriptionId = $decoded[0];
            $this->selectedSubscription = Subscription::with('payments')->findOrFail($subscriptionId);

            // Filter payments of selected year
            $this->payments = $this->selectedSubscription->payments
                ->filter(function ($payment) {
                    return Carbon::parse($payment->date_cover_from)->year == $this->year;
                })
                ->sortBy('date_cover_from');

            // Calculate expected total for the year
            $this->calculateExpectedTotal();
        }
    }

    protected function calculateExpectedTotal()
    {
        if (!$this->selectedSubscription) {
            $this->expectedTotal = 0;
            return;
        }

        // Assuming each subscription has a monthly_fee or amount
        $monthlyFee = $this->selectedSubscription->monthly_fee ?? 0;

        // Calculate expected total based on year
        $startYear = Carbon::parse($this->selectedSubscription->date_start)->year;
        $endYear = Carbon::parse($this->selectedSubscription->date_end)->year ?? $this->year;

        // Number of months to charge in this year
        $fromMonth = $this->year == $startYear ? Carbon::parse($this->selectedSubscription->date_start)->month : 1;
        $toMonth = $this->year == $endYear ? Carbon::parse($this->selectedSubscription->date_end)->month : 12;

        $monthsCount = $toMonth - $fromMonth + 1;
        $this->expectedTotal = $monthsCount * $monthlyFee;
    }

    public function render()
    {
        return view('livewire.billings.billings', [
            'expectedTotal' => $this->expectedTotal,
        ]);
    }
}
