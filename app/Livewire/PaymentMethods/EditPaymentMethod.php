<?php

namespace App\Livewire\PaymentMethods;

use Livewire\Component;
use App\Models\PaymentMethod;
use Hashids\Hashids;

class EditPaymentMethod extends Component
{
    public $paymentMethod;
    public $name;
    public $description;
    public $is_active = true;

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);
        $id = $decoded[0] ?? null;

        if (!$id) {
            abort(404);
        }

        $this->paymentMethod = PaymentMethod::findOrFail($id);

        // Populate the form fields
        $this->name = $this->paymentMethod->name;
        $this->description = $this->paymentMethod->description;
        $this->is_active = (bool) $this->paymentMethod->is_active;
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        $this->paymentMethod->update([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

         $this->dispatch('show-toast', [
            'message' => 'Payment method updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.payment-methods.edit-payment-method');
    }
}
