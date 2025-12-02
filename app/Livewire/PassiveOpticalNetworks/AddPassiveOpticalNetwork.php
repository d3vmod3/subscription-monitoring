<?php

namespace App\Livewire\PassiveOpticalNetworks;

use Livewire\Component;
use App\Models\PassiveOpticalNetwork;
use App\Models\Sector;
use Auth;

class AddPassiveOpticalNetwork extends Component
{
    public $sector_id;
    public $name;
    public $description;
    public $is_active = true;

    public $sectors = [];

    protected $rules = [
        'sector_id' => 'required|exists:sectors,id',
        'name' => 'required|string|max:255|unique:pons,name',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'sector_id.required' => 'The Sector field is required.',
    ];

    public function mount()
    {
        // Fetch all active sectors
        $this->sectors = Sector::where('is_active', true)->orderBy('name')->get();
    }

    public function save()
    {
        if (!Auth::user()->can('add passive optical networks'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();

        PassiveOpticalNetwork::create([
            'sector_id' => $this->sector_id,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        $this->reset(['sector_id', 'name', 'description', 'is_active']);

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
