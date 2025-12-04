<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-700 rounded-lg shadow dark:shadow-lg space-y-6">

    <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Edit Splitter</h2>

    <div class="mt-4">
        <livewire:network-setup.network-setup-dropdowns
            :module="$module"
            :sector-id="$sector_id"
            :pon-id="$pon_id"
            :napbox-id="$napbox_id"
            wire:model.live:napbox_id="napbox_id"
        />
        @error('napbox_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Name --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Name</label>
        <input 
            type="text" 
            wire:model="name" 
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-zinc-400"
            placeholder="Enter splitter name"
        >
        @error('name') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Description</label>
        <textarea 
            wire:model="description" 
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-zinc-400" 
            rows="3"
            placeholder="Optional description..."
        ></textarea>
        @error('description') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Status --}}
    <div class="flex items-center space-x-2 mt-2">
        <flux:switch wire:model="is_active" />
        <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Active</span>
        @error('is_active') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Buttons --}}
    <div class="flex flex-col md:flex-row justify-end gap-2 mt-4">
        <flux:button wire:click="save" wire:loading.attr="disabled" class="bg-zinc-500 hover:bg-zinc-600 text-white rounded px-4 py-2 transition-colors">
            <span wire:loading.remove>Update</span>
            <span wire:loading>Updating...</span>
        </flux:button>

        <flux:link href="{{ route('splitters') }}" class="border flex justify-center rounded-xl px-4 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 dark:hover:text-white transition-colors" style="text-decoration: none;">
            Splitters List
        </flux:link>
    </div>

</div>
