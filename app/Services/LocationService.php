<?php

namespace App\Services;

use App\Models\Region;

class LocationService
{
    /**
     * Get all locations with nested provinces, municipalities, and barangays
     */
    public function getAllLocations()
    {
        return Region::with([
            'provinces.municipalities.barangays'
        ])->get();
    }
}