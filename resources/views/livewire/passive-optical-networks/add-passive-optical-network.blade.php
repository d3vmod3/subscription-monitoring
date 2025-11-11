<div class="space-y-6">
    <h2 class="text-lg font-semibold mb-4">Add New PON</h2>

    {{-- Sector --}}
    <div>
        <label class="block text-sm font-medium mb-1">Sector</label>
        <select 
            wire:model="sector_id" 
            class="w-full border rounded px-3 py-2"
        >
            <option value="">-- Select Sector --</option>
            @foreach($sectors as $sector)
                <option value="{{ $sector->id }}">{{ $sector->name }}</option>
            @endforeach
        </select>
        @error('sector_id') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Name --}}
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input 
            type="text" 
            wire:model="name" 
            class="w-full border rounded px-3 py-2"
            placeholder="Enter PON name"
        >
        @error('name') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea 
            wire:model="description" 
            class="w-full border rounded px-3 py-2" 
            rows="3"
            placeholder="Optional description..."
        ></textarea>
        @error('description') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Status --}}
    <div class="flex justify-end mt-4">
        <flux:field variant="inline">
            <flux:label>Active</flux:label>
            <flux:switch wire:model="is_active" />
            <flux:error name="is_active" />
        </flux:field>
        @error('is_active') 
            <span class="text-red-600 text-sm">{{ $message }}</span> 
        @enderror
    </div>

    {{-- Buttons --}}
    <div class="flex justify-end space-x-2 mt-4">
        <flux:button wire:click="save" variant="primary">Save</flux:button>
    </div>
</div>
