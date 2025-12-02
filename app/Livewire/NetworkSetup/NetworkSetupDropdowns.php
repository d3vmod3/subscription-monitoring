<?php

namespace App\Livewire\NetworkSetup;

use Livewire\Component;
use App\Models\Sector;
use App\Models\PassiveOpticalNetwork as Pon;
use App\Models\Napbox;
use App\Models\Splitter;

class NetworkSetupDropdowns extends Component
{
    public $sectors = [];
    public $pons = [];
    public $napboxes = [];
    public $splitters = [];

    public $selectedSector = null;
    public $selectedPon = null;
    public $selectedNapbox = null;
    public $selectedSplitter = null;
    public $module=[];

    public function mount($module=[],$sectorId = null, $ponId = null, $napboxId = null, $splitterId = null)
    {
        // load all sectors
        $this->sectors = Sector::where('is_active', true)->get();

        $this->selectedSector  = $sectorId;
        $this->selectedPon     = $ponId;
        $this->selectedNapbox  = $napboxId;
        $this->selectedSplitter = $splitterId;

        // --- Load PONS if sector is pre-selected ---
        if ($this->selectedSector) {
            $this->pons = Pon::where('sector_id', $this->selectedSector)
                ->where('is_active', true)
                ->get();
        }

        // --- Load napboxes if pon is pre-selected ---
        if ($this->selectedPon) {
            $this->napboxes = Napbox::where('pon_id', $this->selectedPon)
                ->where('is_active', true)
                ->get();
        }

        // --- Load splitters if napbox is pre-selected ---
        if ($this->selectedNapbox) {
            $this->splitters = Splitter::where('napbox_id', $this->selectedNapbox)
                ->where('is_active', true)
                ->get();
        }
        
    }

    public function updatedSelectedSector($sectorId)
    {
        $this->pons = Pon::where('sector_id', $sectorId)
            ->where('is_active', true)
            ->get();

        $this->selectedPon = null;
        $this->napboxes = [];
        $this->selectedNapbox = null;
        $this->splitters = [];
        $this->selectedSplitter = null;

        $this->dispatch('sector-updated', ['sector_id' => $sectorId]);
    }

    public function updatedSelectedPon($ponId)
    {
        $this->napboxes = Napbox::where('pon_id', $ponId)
            ->where('is_active', true)
            ->get();

        $this->selectedNapbox = null;
        $this->splitters = [];
        $this->selectedSplitter = null;

        $this->dispatch('pon-updated', ['pon_id' => $ponId]);
    }

    public function updatedSelectedNapbox($napboxId)
    {
        $this->splitters = Splitter::where('napbox_id', $napboxId)
            ->where('is_active', true)
            ->get();

        $this->selectedSplitter = null;

        $this->dispatch('napbox-updated', ['napbox_id' => $napboxId]);
    }

    public function updatedSelectedSplitter($splitterId)
    {
        $this->dispatch('splitter-updated', ['splitter_id' => $splitterId]);
    }

    public function render()
    {
        return view('livewire.network-setup.network-setup-dropdowns');
    }
}
