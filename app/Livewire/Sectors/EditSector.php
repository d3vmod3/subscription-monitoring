<?php

namespace App\Livewire\Sectors;

use Livewire\Component;
use App\Models\Sector;
use Hashids\Hashids;
use Auth;

class EditSector extends Component
{
    public $sectorId;
    public $name;
    public $description;
    public $is_active;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:sectors,name,' . $this->sectorId,
            'description' => 'nullable|string|max:500',
        ];
    }

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);

        abort_if(empty($decoded), 404, 'Invalid Sector');

        $this->sectorId = $decoded[0];

        $sector = Sector::findOrFail($this->sectorId);
        $this->name = $sector->name;
        $this->description = $sector->description;
        $this->is_active = (bool) $sector->is_active;
    }

    public function save()
    {
        if (!Auth::user()->can('edit sectors'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();

        $sector = Sector::findOrFail($this->sectorId);
        $sector->update([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Sector updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        if (!Auth::user()->can('edit sectors'))
        {
            abort(403, 'You are not allowed to this page');
        }
        return view('livewire.sectors.edit-sector');
    }
}
