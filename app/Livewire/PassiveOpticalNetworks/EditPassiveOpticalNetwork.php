<?php

namespace App\Livewire\PassiveOpticalNetworks;

use Livewire\Component;
use App\Models\PassiveOpticalNetwork;
use App\Models\Sector;
use Hashids\Hashids;

class EditPassiveOpticalNetwork extends Component
{
    public $ponId;
    public $name;
    public $description;
    public $is_active;
    public $sector_id;
    public $sectors = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:pons,name,' . $this->ponId,
            'sector_id' => 'required|exists:sectors,id',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);
        abort_if(empty($decoded), 404, 'Invalid PON');

        $this->ponId = $decoded[0];

        $pon = PassiveOpticalNetwork::findOrFail($this->ponId);

        $this->name = $pon->name;
        $this->description = $pon->description;
        $this->is_active = (bool) $pon->is_active;
        $this->sector_id = $pon->sector_id;

        // Load all active sectors for the dropdown
        $this->sectors = Sector::where('is_active', true)->orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        $pon = PassiveOpticalNetwork::findOrFail($this->ponId);
        $pon->update([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'sector_id' => $this->sector_id,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'PON updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.passive-optical-networks.edit-passive-optical-network');
    }
}
