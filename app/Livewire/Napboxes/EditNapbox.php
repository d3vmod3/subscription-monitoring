<?php

namespace App\Livewire\Napboxes;

use Livewire\Component;
use App\Models\Napbox;
use App\Models\PassiveOpticalNetwork;
use App\Models\Splitter;
use Hashids\Hashids;

class EditNapbox extends Component
{
    public $napboxId;
    public $napbox_code;
    public $name;
    public $description;
    public $is_active;
    public $pon_id;
    public $splitter_id;

    public $pons;
    public $splitters;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'pon_id' => 'required|exists:pons,id',
            'splitter_id' => 'required|exists:splitters,id',
        ];
    }

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);

        abort_if(empty($decoded), 404, 'Invalid Napbox');

        $this->napboxId = $decoded[0];

        $napbox = Napbox::findOrFail($this->napboxId);

        $this->napbox_code = $napbox->napbox_code;
        $this->name = $napbox->name;
        $this->description = $napbox->description;
        $this->is_active = (bool) $napbox->is_active;
        $this->pon_id = $napbox->pon_id;
        $this->splitter_id = $napbox->splitter_id;

        $this->pons = PassiveOpticalNetwork::where('is_active', true)->get();
        $this->splitters = Splitter::where('is_active', true)->get();
    }

    public function save()
    {
        $this->validate();

        $napbox = Napbox::findOrFail($this->napboxId);

        $napbox->update([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'pon_id' => $this->pon_id,
            'splitter_id' => $this->splitter_id,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Napbox updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.napboxes.edit-napbox');
    }
}
