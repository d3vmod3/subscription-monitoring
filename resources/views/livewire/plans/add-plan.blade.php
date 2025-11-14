<div class="space-y-6 p-4">

    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Add New Plan</h2>

    {{-- Plan Name --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Plan Name</label>
        <input type="text" wire:model.defer="name" 
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-zinc-400"
            placeholder="Enter plan name">
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Description</label>
        <textarea wire:model.defer="description" 
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-zinc-400" 
            rows="3" placeholder="Optional description"></textarea>
        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Price --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Price</label>
        <input type="number" wire:model.defer="price" step="0.01" 
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-zinc-400"
            placeholder="Enter price">
        @error('price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Status --}}
    <div class="flex items-center justify-end mt-2">
        <flux:switch wire:model.defer="is_active" />
        <span class="ml-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Active</span>
        @error('is_active') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Buttons --}}
    <div class="flex justify-end space-x-2 mt-4">
        <flux:button wire:click="save" variant="primary">Save</flux:button>
    </div>

</div>
