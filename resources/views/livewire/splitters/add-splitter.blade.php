<div class="space-y-6">

    <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Add New Splitter</h2>

    {{-- ✅ Napbox Select --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Napbox</label>
        <select 
            wire:model="napbox_id" 
            class="w-full border border-gray-300 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white dark:bg-zinc-800"
        >
            <option value="">-- Select Napbox --</option>
            @foreach($napboxes as $napbox)
                <option value="{{ $napbox->id }}">
                    {{ $napbox->name }} ({{ $napbox->name ?? 'No Napbox' }})
                </option>
            @endforeach
        </select>
        @error('napbox_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Name --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Name</label>
        <input 
            type="text" 
            wire:model.defer="name" 
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
            wire:model.defer="description" 
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-zinc-400" 
            rows="3"
            placeholder="Optional description..."
        ></textarea>
        @error('description') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Status --}}
    <div class="flex items-center space-x-2">
        <flux:switch wire:model.defer="is_active" />
        <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Active</span>
        @error('is_active') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Buttons --}}
    <div class="flex justify-end mt-4">
        <flux:button wire:click="save" variant="primary">
            Save
        </flux:button>
    </div>

</div>
