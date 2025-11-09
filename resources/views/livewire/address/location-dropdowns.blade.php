<div class="space-y-4">

    <!-- Region -->
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Region</label>
        <select wire:model.live="selectedRegion"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            <option value="">Select Region</option>
            @foreach($locations as $region)
                <option value="{{ $region->region_id }}">{{ $region->region_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Province -->
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Province</label>
        <select wire:model.live="selectedProvince"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
            @if(!$provinces || $provinces->isEmpty()) disabled @endif>
            <option value="">Select Province</option>
            @foreach($provinces as $province)
                <option value="{{ $province->province_id }}">{{ $province->province_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Municipality -->
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Municipality</label>
        <select wire:model.live="selectedMunicipality"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
            @if(!$municipalities || $municipalities->isEmpty()) disabled @endif>
            <option value="">Select Municipality</option>
            @foreach($municipalities as $municipality)
                <option value="{{ $municipality->municipality_id }}">{{ $municipality->municipality_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Barangay -->
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Barangay</label>
        <select wire:model.live="selectedBarangay"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
            @if(!$barangays || $barangays->isEmpty()) disabled @endif>
            <option value="">Select Barangay</option>
            @foreach($barangays as $barangay)
                <option value="{{ $barangay->barangay_id }}">{{ $barangay->barangay_name }}</option>
            @endforeach
        </select>
    </div>
</div>
