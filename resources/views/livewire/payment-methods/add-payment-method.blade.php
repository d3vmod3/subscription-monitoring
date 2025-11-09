<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-700 rounded-lg shadow dark:shadow-lg">
    @if (session()->has('message'))
        <div class="mb-4 text-green-600 font-medium">
            {{ session('message') }}
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-6 text-zinc-900 dark:text-zinc-100">Add Payment Method</h2>

    <form wire:submit.prevent="save" class="space-y-4">
        {{-- Name --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Name</label>
            <input type="text" wire:model="name" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Description</label>
            <textarea wire:model="description"
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"></textarea>
            @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
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
        <div class="flex flex-col md:flex-row justify-between mt-4 gap-4">
            <flux:link class="border flex justify-center rounded-xl p-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 dark:hover:text-white transition-colors duration-150"
                href="{{ route('payment-methods') }}" style="text-decoration: none;">
                Back
            </flux:link>

            <button type="submit"
                class="bg-zinc-500 text-white px-4 py-2 cursor-pointer rounded hover:bg-zinc-600 dark:hover:bg-zinc-700 transition-colors">
                Save Payment Method
            </button>
        </div>
    </form>
</div>
