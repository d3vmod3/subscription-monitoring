<?php

namespace App\Livewire\Address;

use Livewire\Component;
use App\Services\LocationService;
use Illuminate\Support\Collection;

class LocationDropdowns extends Component
{
    public $locations; // all regions with nested provinces, municipalities, barangays

    public $selectedRegion = null;
    public $selectedProvince = null;
    public $selectedMunicipality = null;
    public $selectedBarangay = null;

    public $provinces = [];
    public $municipalities = [];
    public $barangays = [];

    public function mount(LocationService $service, $regionId = null, $provinceId = null, $municipalityId = null, $barangayId = null)
    {
        // getAllLocations() should return nested objects:
        // Region -> Provinces -> Municipalities -> Barangays
        $this->locations = collect($service->getAllLocations());
         // set initial selections if provided
        $this->selectedRegion = $regionId;
        $this->selectedProvince = $provinceId;
        $this->selectedMunicipality = $municipalityId;
        $this->selectedBarangay = $barangayId;
        // load dependent dropdowns
        if ($this->selectedRegion) {
            $region = $this->locations->firstWhere('region_id', $this->selectedRegion);
            $this->provinces = $region ? collect($region->provinces) : collect([]);
        }

        if ($this->selectedProvince) {
            $province = collect($this->provinces)->firstWhere('province_id', $this->selectedProvince);
            $this->municipalities = $province ? collect($province->municipalities) : collect([]);
        }

        if ($this->selectedMunicipality) {
            $municipality = collect($this->municipalities)->firstWhere('municipality_id', $this->selectedMunicipality);
            $this->barangays = $municipality ? collect($municipality->barangays) : collect([]);
        }
    }

    public function updatedSelectedRegion($regionId)
    {
        $region = $this->locations->firstWhere('region_id', $regionId);
        $this->provinces = $region ? collect($region->provinces) : collect([]);
        $this->selectedProvince = null;
        $this->municipalities = collect([]);
        $this->selectedMunicipality = null;
        $this->barangays = collect([]);
        $this->selectedBarangay = null;

        $this->dispatch('region-updated', ['region_id' => $regionId]); // <-- emit to parent
    }

    public function updatedSelectedProvince($provinceId)
    {
        $province = collect($this->provinces)->firstWhere('province_id', $provinceId);
        $this->municipalities = $province ? collect($province->municipalities) : collect([]);
        $this->selectedMunicipality = null;
        $this->barangays = collect([]);
        $this->selectedBarangay = null;

        $this->dispatch('province-updated', ['province_id' => $provinceId]);
    }

    public function updatedSelectedMunicipality($municipalityId)
    {
        $municipality = collect($this->municipalities)->firstWhere('municipality_id', $municipalityId);
        $this->barangays = $municipality ? collect($municipality->barangays) : collect([]);
        $this->selectedBarangay = null;
        $this->dispatch('municipality-updated', ['municipality_id' => $municipalityId]);
    }

    public function updatedSelectedBarangay($barangayId)
    {
        $barangay = collect($this->barangays)->firstWhere('barangay_id', $barangayId);
        $this->selectedBarangay = $barangayId;
        $this->dispatch('barangay-updated', ['barangay_id' => $barangayId]);
    }


    public function render()
    {
        return view('livewire.address.location-dropdowns');
    }
}
