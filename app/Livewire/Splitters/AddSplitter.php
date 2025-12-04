<?php

namespace App\Livewire\Splitters;

use Livewire\Component;
use App\Models\Splitter;
use App\Models\Napbox;
use Auth;

class AddSplitter extends Component
{
    public $name;
    public $description;
    public $is_active = true;
    public $napboxes; // For Napboxes dropdown
    public $napbox_id;
    public $module=["sector","pon","napbox"];

    protected $rules = [
        'napbox_id' => 'required|exists:napboxes,id',
        'name' => 'required|string|max:255|unique:splitters,name',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'napbox_id.required' => 'The Napbox field is required .',
    ];

    protected $listeners = [
        'splitter-added' => '$refresh',
        'napbox-updated' => 'setNapbox',
    ];

    public function setNapbox($data)
    {
        $this->napbox_id = $data['napbox_id'];
    }

    public function mount()
    {
        $this->napboxes = Napbox::where('is_active', true)->orderBy('name')->get();
    }

    public function save()
    {
        if (!Auth::user()->can('add splitters'))
        {
             abort(403, 'Unauthorized action');
        }
        $this->validate();
        Splitter::create([
            'name' => $this->name,
            'napbox_id' => $this->napbox_id,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        // Reset inputs
        $this->reset(['name', 'description', 'is_active','napbox_id']);

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
