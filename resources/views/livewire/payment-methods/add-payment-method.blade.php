<div class="space-y-6 p-4">
    {{-- ✅ Success Message --}}
    @if (session()->has('message'))
        <div class="mb-4 text-green-600 font-medium">
            {{ session('message') }}
        </div>
    @endif

    {{-- 🧾 Title --}}
    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Add Payment Method</h2>

    {{-- 🧩 Form --}}
    <form wire:submit.prevent="save" class="space-y-4">

        {{-- Name --}}
        <div>
            <label class="block text-sm font-medium text-zinc-900 dark:text-zinc-100">Name</label>
            <input type="text" wire:model="name"
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
                placeholder="Enter payment method name">
            @error('name') 
                <span class="text-red-600 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-zinc-900 dark:text-zinc-100">Description</label>
            <textarea wire:model="description"
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
                rows="3" placeholder="Optional description"></textarea>
            @error('description') 
                <span class="text-red-600 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        {{-- Status --}}
        <div class="flex justify-end mt-4">
            <flux:field variant="inline">
                <flux:label>Active</flux:label>
                <flux:switch wire:model="is_active" />
                <flux:error name="is_active" />
            </flux:field>
            @error('is_active') 
                <span class="text-red-600 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end mt-4">
            <flux:button type="submit"
                variant="primary">
                Save Payment Method
            </flux:button>
        </div>
    </form>
</div>
