<?php

namespace App\Livewire\PaymentMethods;

use Livewire\Component;
use App\Models\PaymentMethod;
use Hashids\Hashids;

class AddPaymentMethod extends Component
{
    public $name;
    public $description;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255|unique:payment_methods,name',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        $paymentMethod = PaymentMethod::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        // Encode ID using Hashids
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $hash = $hashids->encode($paymentMethod->id);
        $this->dispatch('method-added');

        $this->reset(['name', 'description','is_active']);
        $this->dispatch('show-toast', [
            'message' => 'Payment method added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);

        // return redirect()->route('payment-methods.edit', ['hash' => $hash]);
    }

    public function render()
    {
        return view('livewire.payment-methods.add-payment-method');
    }
}
