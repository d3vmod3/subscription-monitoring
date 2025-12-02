<?php

namespace App\Livewire\Napboxes;

use Livewire\Component;
use App\Models\Napbox;
use App\Models\PassiveOpticalNetwork;
// use App\Models\Splitter;
use Auth;

class AddNapbox extends Component
{
    public $pon_id;
    // public $splitter_id;
    public $napbox_code;
    public $name;
    public $description;
    public $is_active = true;

    public $pons;      // For PON dropdown
    // public $splitters; // For Splitter dropdown
    public $module=["sector","pon"];
    protected $rules = [
        'pon_id' => 'required|exists:pons,id',
        // 'splitter_id' => 'exists:splitters,id',
        'napbox_code' => 'required|string|max:50|unique:napboxes,napbox_code',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'pon_id.required' => 'PON is a required field.',
    ];

    public function mount()
    {
        // Load active PONs and Splitters for dropdowns
        $this->pons = PassiveOpticalNetwork::where('is_active', true)->orderBy('name')->get();
        // $this->splitters = Splitter::where('is_active', true)->orderBy('name')->get();
    }

    protected $listeners = [
        'pon-updated' => 'setPon',
    ];

    public function setPon($data)
    {
        $this->pon_id = $data['pon_id'];
    }

    public function save()
    {
        if (!Auth::user()->can('add napboxes'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();

        Napbox::create([
            'pon_id' => $this->pon_id,
            'napbox_code' => $this->napbox_code,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        $this->reset(['pon_id', 'napbox_code', 'name', 'description', 'is_active']);

        $this->dispatch('napbox-added');

        $this->dispatch('show-toast', [
            'message' => 'Napbox added successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.napboxes.add-napbox');
    }
}
