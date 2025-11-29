<?php

namespace App\Livewire\Plans;

use Livewire\Component;
use App\Models\Plan;
use Auth;

class AddPlan extends Component
{
    public $name;
    public $description;
    public $price;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255|unique:plans,name',
        'description' => 'nullable|string|max:1000',
        'price' => 'required|numeric|min:0',
        'is_active' => 'boolean',
    ];


    public function save()
    {
        if (!Auth::user()->can('add plans'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();

        Plan::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->is_active,
        ]);

        // Reset form fields after adding
        $this->reset(['name', 'description', 'price', 'is_active']);
        $this->dispatch('plan-added');
        // Dispatch event for toast notification
        $this->dispatch('show-toast', [
            'message' => 'Plan added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.plans.add-plan');
    }
}
