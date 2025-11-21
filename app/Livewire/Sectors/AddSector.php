<?php

namespace App\Livewire\Sectors;

use Livewire\Component;
use App\Models\Sector;
use Auth;

class AddSector extends Component
{
    public $name;
    public $description;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255|unique:sectors,name',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    protected $listeners = ['sector-added' => '$refresh'];

    public function save()
    {
        if (!Auth::user()->can('add sectors'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();

        Sector::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('sector-added');
        $this->reset(['name', 'description','is_active']);
        $this->dispatch('show-toast', [
            'message' => 'Sector added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
        
    }

    public function render()
    {
        return view('livewire.sectors.add-sector');
    }
}
