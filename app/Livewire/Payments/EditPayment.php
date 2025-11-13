<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use App\Models\Payment;
use Hashids\Hashids;

class EditPayment extends Component
{
    public $paymentId;
    public $payment;

    // Editable
    public $status; // Pending, Approved, Disapproved
    public $paid_amount;
    public $month_year_cover;
    public $is_discounted;
    public $remarks;
    public $account_name;

    public $selectedSubscription; // to show plan info
    public $total_paid = 0;

    protected function rules()
    {
        return [
            'status' => 'required|in:Pending,Approved,Disapproved',
            'paid_amount' => 'required|numeric|min:0',
            'month_year_cover' => 'required|date_format:Y-m',
            'account_name' => 'required|string|max:255',
            'is_discounted' => 'boolean',
            'remarks' => 'nullable|string|required_if:is_discounted,true',
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
        $this->remarks = $this->payment->remarks;
        $this->account_name = $this->payment->account_name;

        // Load subscription info to display plan name & price
        $this->selectedSubscription = $this->payment->subscription;

        // Compute total paid for the same subscription and month_year_cover
        $this->computeTotalPaid();
    }

    public function computeTotalPaid()
    {
        if (!$this->selectedSubscription || !$this->month_year_cover) {
            $this->total_paid = 0;
            return;
        }

        $this->total_paid = Payment::where('subscription_id', $this->selectedSubscription->id)
            ->where('month_year_cover', $this->month_year_cover)
            ->sum('paid_amount');
    }

    public function save()
    {
        $this->validate();

        $this->payment->update([
            'status' => $this->status,
            'paid_amount' => $this->paid_amount,
            'month_year_cover' => $this->month_year_cover,
            'is_discounted' => $this->is_discounted,
            'remarks' => $this->is_discounted ? $this->remarks : null,
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
