<?php

namespace App\Livewire\PassiveOpticalNetworks;

use Livewire\Component;
use App\Models\PassiveOpticalNetwork;

class AddPassiveOpticalNetwork extends Component
{
    public $name;
    public $description;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255|unique:pons,name',
        'description' => 'nullable|string|max:500',
    ];

    public function save()
    {
        $this->validate();

        PassiveOpticalNetwork::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        // Reset inputs after save
        $this->reset(['name', 'description','is_active']);

        // Trigger event to refresh list in parent component
        $this->dispatch('pon-added');

        $this->dispatch('show-toast', [
            'message' => 'PON added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.passive-optical-networks.add-passive-optical-network');
    }
}
