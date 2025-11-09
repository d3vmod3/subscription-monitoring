<?php

namespace App\Livewire\Sectors;

use Livewire\Component;
use App\Models\Sector;

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

    public function save()
    {
        $this->validate();

        Sector::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Sector added successfully!');
        return redirect()->route('sectors');
    }

    public function render()
    {
        return view('livewire.sectors.add-sector');
    }
}
