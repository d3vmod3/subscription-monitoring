<div class="p-4 sm:p-6 max-w-2xl mx-auto bg-white dark:bg-zinc-800 rounded-xl shadow-sm dark:shadow-lg space-y-6">
    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Edit PON</h2>

    {{-- Sector --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Sector</label>
        <select 
            wire:model="sector_id" 
            class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >
            <option value="">-- Select Sector --</option>
            @foreach ($sectors as $sector)
                <option value="{{ $sector->id }}">{{ $sector->name }}</option>
            @endforeach
        </select>
        @error('sector_id') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Name --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Name</label>
        <input 
            type="text" 
            wire:model="name" 
            placeholder="Enter PON name"
            class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >
        @error('name') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
        <textarea 
            wire:model="description" 
            rows="3"
            placeholder="Optional description..."
            class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-800 focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none"
        ></textarea>
        @error('description') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Status --}}
    <div class="flex justify-end">
        <flux:field variant="inline">
            <flux:label>Active</flux:label>
            <flux:switch wire:model="is_active" />
            <flux:error name="is_active" />
        </flux:field>
        @error('is_active') 
            <span class="text-red-600 text-sm ml-2">{{ $message }}</span> 
        @enderror
    </div>

    {{-- Buttons --}}
    <div class="flex flex-wrap justify-end gap-2 pt-4 border-t border-gray-200 dark:border-zinc-700">
        <flux:button wire:click="save" wire:loading.attr="disabled" variant="primary">
            <span wire:loading.remove>Update</span>
            <span wire:loading>Updating...</span>
        </flux:button>

        <flux:link 
            href="{{ route('pons') }}" 
            variant="secondary"
            class="border border-gray-300 dark:border-zinc-600 text-sm rounded-lg px-4 py-2 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors duration-150"
            style="text-decoration: none;"
        >
            PONs List
        </flux:link>
    </div>
</div>
