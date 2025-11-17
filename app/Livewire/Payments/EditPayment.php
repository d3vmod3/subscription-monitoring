<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use App\Models\Payment;
use Hashids\Hashids;
use Carbon\Carbon;

class EditPayment extends Component
{
    public $paymentId;
    public $payment;

    // Editable
    public $status; // Pending, Approved, Disapproved
    public $paid_amount;
    public $month_year_cover;
    public $is_discounted;
    public $discount_amount;
    public $remarks;
    public $account_name;

    public $selectedSubscription; // to show plan info
    public $total_paid = 0;
    public $expected_amount=0.0;

    private $created_at;
    private $updated_at;

    protected function rules()
    {
        return [
            'status' => 'required|in:Pending,Approved,Disapproved',
        ];
    }

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);

        abort_if(empty($decoded), 404, 'Invalid Payment');

        $this->paymentId = $decoded[0];

        $this->payment = Payment::with('subscription.plan', 'subscription.subscriber', 'paymentMethod')
            ->findOrFail($this->paymentId);

        // Map current values to editable fields
        $this->status = $this->payment->status;
        $this->paid_amount = $this->payment->paid_amount;
        $this->month_year_cover = $this->payment->month_year_cover;
        $this->is_discounted = $this->payment->is_discounted;
        $this->discount_amount = $this->payment->discount_amount;
        $this->remarks = $this->payment->remarks;
        $this->account_name = $this->payment->account_name;


        // Load subscription info to display plan name & price
        $this->selectedSubscription = $this->payment->subscription;

        // Compute total paid for the same subscription and month_year_cover
        $this->computeTotalPaid();
        $this->computeExpectedAmount();
    }

    public function computeTotalPaid()
    {
        if (!$this->selectedSubscription || !$this->month_year_cover) {
            $this->total_paid = 0;
            return;
        }

        $this->total_paid = Payment::where('subscription_id', $this->selectedSubscription->id)
            ->where('month_year_cover', $this->month_year_cover)
            ->where('status', 'Approved')
            ->sum('paid_amount');
    }

    public function computeExpectedAmount()
    {
        $this->expected_amount = 0;

        if (!$this->selectedSubscription || !$this->selectedSubscription->plan || !$this->month_year_cover) {
            return;
        }

        $planPrice = $this->selectedSubscription->plan->price;
        $subscriptionStart = Carbon::parse($this->selectedSubscription->start_date);
        $coverMonthStart = Carbon::parse($this->month_year_cover . '-01');

        // First month scenario: subscription starts mid-month
        if ($subscriptionStart->format('Y-m') == $coverMonthStart->format('Y-m')) {
            // Set the end date as the 7th of next month
            $coverMonthEnd = $coverMonthStart->copy()->addMonth()->day(1);

            // Calculate active days from start date until 7th of next month
            $activeDays = $subscriptionStart->diffInDays($coverMonthEnd);

            $totalDaysInMonth = $coverMonthStart->daysInMonth;
            $expected = round(($planPrice / $totalDaysInMonth) * $activeDays);

        } else {
            $expected = $planPrice;
        }

        // Subtract any payments already made for this subscription in this month
        $alreadyPaid = Payment::where('subscription_id', $this->selectedSubscription->id)
            ->where('month_year_cover', $this->month_year_cover)
            ->where('status', 'Approved')
            ->sum('paid_amount');

        $this->expected_amount = max($expected - $alreadyPaid, 0);
    }

    public function save()
    {
        $this->validate();
        $this->payment->update([
            'status' => $this->status,
            'paid_amount' => $this->paid_amount,
            'month_year_cover' => $this->month_year_cover,
            'is_discounted' => $this->is_discounted,
            'remarks' => $this->remarks,
            'account_name' => $this->account_name,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Payment updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);

        
    }

    public function render()
    {
        return view('livewire.payments.edit-payment');
    }
}
