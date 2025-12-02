<div class="p-4 sm:p-6 max-w-2xl mx-auto bg-white dark:bg-zinc-800 rounded-xl shadow-sm dark:shadow-lg space-y-6">
    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Edit Napbox</h2>

    {{-- ✅ PON --}}
    <div>
        <livewire:network-setup.network-setup-dropdowns
            :module="$module"
            :sector-id="$sector_id"
            :pon-id="$pon_id"
            :napbox-id="$napbox_id"
            wire:model.live:napbox_id="napbox_id" />
        @error('pon_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- ✅ Napbox Code --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Napbox Code</label>
        <input 
            type="text" 
            wire:model="napbox_code" 
            class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >
    </div>

    {{-- ✅ Name --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Name</label>
        <input 
            type="text" 
            wire:model="name"
            placeholder="Enter Napbox name"
            class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- ✅ Description --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
        <textarea 
            wire:model="description" 
            rows="3"
            placeholder="Optional description"
            class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none"
        ></textarea>
        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    


    {{-- ✅ Status Switch --}}
    <div class="flex justify-end">
        <flux:field variant="inline">
            <flux:label>Active</flux:label>
            <flux:switch wire:model="is_active" />
            <flux:error name="is_active" />
        </flux:field>
    </div>

    {{-- ✅ Buttons --}}
    <div class="flex justify-end space-x-2 pt-4 border-gray-200 dark:border-zinc-700">
        <flux:button 
            wire:click="save" 
            wire:loading.attr="disabled" 
            variant="primary"
        >
            <span wire:loading.remove>Update</span>
            <span wire:loading>Updating...</span>
        </flux:button>

        <flux:link 
            href="{{ route('napboxes') }}" 
            variant="secondary"
            class="border border-gray-300 dark:border-zinc-600 text-sm rounded-lg px-4 py-2 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors duration-150"
            style="text-decoration: none;"
        >
            Napboxes List
        </flux:link>
    </div>
</div>
