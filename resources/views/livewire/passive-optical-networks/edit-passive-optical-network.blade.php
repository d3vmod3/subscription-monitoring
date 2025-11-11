<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-700 rounded-lg shadow space-y-6 dark:shadow-lg">
    <h2 class="text-lg font-semibold mb-4">
         Edit PON
    </h2>
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
        @error('is_active') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>
    <div class="flex justify-end space-x-2 mt-4">
        <flux:button wire:click="save" wire:loading.attr="disabled" variant="primary">
            <span wire:loading.remove>Update</span>
            <span wire:loading>Updating...</span>
        </flux:button>
        <flux:link href="{{ route('pons') }}" variant="secondary" class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150" style="text-decoration: none;">PONs List</flux:link>
    </div>
</div>