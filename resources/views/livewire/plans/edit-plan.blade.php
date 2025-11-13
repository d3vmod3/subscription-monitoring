<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-700 rounded-lg shadow space-y-6 dark:shadow-lg">

    <h2 class="text-2xl font-bold mb-6 text-zinc-900 dark:text-zinc-100">Edit Plan</h2>

    {{-- Name & Subscription Interval (side-by-side on md+) --}}
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Name</label>
            <input type="text" wire:model="name" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex-1">
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Subscription Interval</label>
            <select wire:model="subscription_interval"
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
                <option value="monthly">Monthly</option>
                <option value="6 months">6 Months</option>
                <option value="yearly">Yearly</option>
            </select>
            @error('subscription_interval') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Description --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Description</label>
        <textarea wire:model="description"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"></textarea>
        @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Price --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Price</label>
        <input type="number" step="0.01" wire:model="price"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
        @error('price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
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

    {{-- Buttons --}}
    <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-2 mt-4">
        <flux:button wire:click="save" wire:loading.attr="disabled" variant="primary">
            <span wire:loading.remove>Update</span>
            <span wire:loading>Updating...</span>
        </flux:button>
        <flux:link href="{{ route('plans') }}" variant="secondary" 
            class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150" 
            style="text-decoration: none;">
            Plans List
        </flux:link>
    </div>

</div>
