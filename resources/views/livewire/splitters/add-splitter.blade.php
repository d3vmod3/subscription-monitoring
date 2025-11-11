<div class="space-y-6">
    <h2 class="text-lg font-semibold">Add New Splitter</h2>

    {{-- Name --}}
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input 
            type="text" 
            wire:model.defer="name" 
            class="w-full border rounded px-3 py-2"
            placeholder="Enter splitter name"
        >
        @error('name') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea 
            wire:model.defer="description" 
            class="w-full border rounded px-3 py-2" 
            rows="3"
            placeholder="Optional description..."
        ></textarea>
        @error('description') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Status --}}
    <div class="flex items-center justify-end mt-2">
        <flux:switch wire:model.defer="is_active" />
        <span class="ml-2 text-sm font-medium">Active</span>
        @error('is_active') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Buttons --}}
    <div class="flex justify-end space-x-2 mt-4">
        <flux:button wire:click="save" variant="primary">Save</flux:button>
    </div>
</div>
