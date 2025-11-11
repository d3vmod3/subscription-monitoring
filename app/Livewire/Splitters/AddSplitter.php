<?php

namespace App\Livewire\Splitters;

use Livewire\Component;
use App\Models\Splitter;

class AddSplitter extends Component
{
    public $name;
    public $description;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255|unique:splitters,name',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    protected $listeners = ['splitter-added' => '$refresh'];

    public function save()
    {
        $this->validate();

        Splitter::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        // Reset inputs
        $this->reset(['name', 'description', 'is_active']);

        // Trigger refresh in parent
        $this->dispatch('splitter-added');

        // Show toast notification
        $this->dispatch('show-toast', [
            'message' => 'Splitter added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.splitters.add-splitter');
    }
}
