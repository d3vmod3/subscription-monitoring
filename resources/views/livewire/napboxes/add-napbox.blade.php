<div class="space-y-6 p-4">
    <h2 class="text-lg font-semibold mb-4">Add New Napbox</h2>

    {{-- PON Select --}}
    <div>
        <label class="block text-sm font-medium mb-1">PON</label>
        <select wire:model="pon_id" class="w-full border rounded px-3 py-2">
            <option value="">-- Select PON --</option>
            @foreach($pons as $pon)
                <option value="{{ $pon->id }}">
                    {{ $pon->name }} ({{ $pon->sector->name ?? 'No sector' }})
                </option>
            @endforeach
        </select>
        @error('pon_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>


    <div>
        <label>Splitter</label>
        <select wire:model="splitter_id" class="w-full border rounded px-3 py-2">
            <option value="">Select Splitter</option>
            @foreach($splitters as $splitter)
                <option value="{{ $splitter->id }}">{{ $splitter->name }}</option>
            @endforeach
        </select>
        @error('splitter_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Napbox Code --}}
    <div>
        <label class="block text-sm font-medium mb-1">Napbox Code</label>
        <input type="text" wire:model="napbox_code" class="w-full border rounded px-3 py-2" placeholder="Enter Napbox code">
        @error('napbox_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Name --}}
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input type="text" wire:model="name" class="w-full border rounded px-3 py-2" placeholder="Enter Napbox name">
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea wire:model="description" class="w-full border rounded px-3 py-2" rows="3" placeholder="Optional description"></textarea>
        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Status --}}
    <div class="flex justify-end mt-4">
        <flux:field variant="inline">
            <flux:label>Active</flux:label>
            <flux:switch wire:model="is_active" />
            <flux:error name="is_active" />
        </flux:field>
    </div>

    {{-- Buttons --}}
    <div class="flex justify-end space-x-2 mt-4">
        <flux:button wire:click="save" variant="primary">Save</flux:button>
    </div>
</div>
