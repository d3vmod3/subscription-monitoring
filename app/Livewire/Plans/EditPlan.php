<?php

namespace App\Livewire\Plans;

use Livewire\Component;
use App\Models\Plan;
use Hashids\Hashids;

class EditPlan extends Component
{
    public $planId;
    public $name;
    public $description;
    public $price;
    public $is_active = true;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:plans,name,' . $this->planId,
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);

        abort_if(empty($decoded), 404, 'Invalid Plan');

        $this->planId = $decoded[0];

        $plan = Plan::findOrFail($this->planId);
        $this->name = $plan->name;
        $this->description = $plan->description;
        $this->price = $plan->price;
        $this->is_active = (bool) $plan->is_active;
    }

    public function save()
    {
        $this->validate();

        $plan = Plan::findOrFail($this->planId);
        $plan->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Plan updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.plans.edit-plan');
    }
}
