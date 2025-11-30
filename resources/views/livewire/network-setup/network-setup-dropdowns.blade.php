<div class="space-y-4">

    <!-- Sector -->
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Sector</label>
        <select wire:model.live="selectedSector"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            <option value="">Select Sector</option>
            @foreach($sectors as $sector)
                <option value="{{ $sector->id }}">{{ $sector->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- PON -->
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">PON</label>
        <select wire:model.live="selectedPon"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
            @if(empty($pons)) disabled @endif>
            <option value="">Select PON</option>
            @foreach($pons as $pon)
                <option value="{{ $pon->id }}">{{ $pon->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Napbox -->
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Napbox</label>
        <select wire:model.live="selectedNapbox"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
            @if(empty($napboxes)) disabled @endif>
            <option value="">Select Napbox</option>
            @foreach($napboxes as $napbox)
                <option value="{{ $napbox->id }}">
                    {{ $napbox->napbox_code }} — {{ $napbox->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Splitter -->
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Splitter</label>
        <select wire:model.live="selectedSplitter"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
            @if(empty($splitters)) disabled @endif>
            <option value="">Select Splitter</option>
            @foreach($splitters as $splitter)
                <option value="{{ $splitter->id }}">{{ $splitter->name }}</option>
            @endforeach
        </select>
    </div>

</div>
