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
    public $status; // pending, approved, disapproved

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

        $this->payment = Payment::with('subscription.subscriber', 'paymentMethod')->findOrFail($this->paymentId);

        // Map current boolean/status to string for dropdown
        $this->status = $this->payment->status;
    }

    public function save()
    {
        $this->validate();

        $this->payment->update([
            'status' => $this->status,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Payment status updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.payments.edit-payment');
    }
}
