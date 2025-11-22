<div class="p-4 sm:p-6 max-w-2xl mx-auto space-y-6">
    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Add New Napbox</h2>

    {{-- ✅ PON Select --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">PON</label>
        <select 
            wire:model="pon_id" 
            class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white dark:bg-zinc-800"
        >
            <option value="">-- Select PON --</option>
            @foreach($pons as $pon)
                <option value="{{ $pon->id }}">
                    {{ $pon->name }} ({{ $pon->sector->name ?? 'No sector' }})
                </option>
            @endforeach
        </select>
        @error('pon_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- ✅ Napbox Code --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Napbox Code</label>
        <input 
            type="text" 
            wire:model="napbox_code" 
            placeholder="Enter Napbox code"
            class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white dark:bg-zinc-800"
        >
        @error('napbox_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- ✅ Name --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Name</label>
        <input 
            type="text" 
            wire:model="name" 
            placeholder="Enter Napbox name"
            class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white dark:bg-zinc-800"
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
            class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white dark:bg-zinc-800 resize-none"
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
        <flux:button wire:click="save" variant="primary">
            Save
        </flux:button>
    </div>
</div>
