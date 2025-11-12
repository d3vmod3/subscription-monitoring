<div class="space-y-6 p-4">
    <h2 class="text-lg font-semibold mb-4">Add New Plan</h2>

    {{-- Plan Name --}}
    <div>
        <label class="block text-sm font-medium mb-1">Plan Name</label>
        <input type="text" wire:model.defer="name" class="w-full border rounded px-3 py-2" placeholder="Enter plan name">
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea wire:model.defer="description" class="w-full border rounded px-3 py-2" rows="3" placeholder="Optional description"></textarea>
        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Subscription Interval --}}
    <div>
        <label class="block text-sm font-medium mb-1">Subscription Interval</label>
        <select wire:model.defer="subscription_interval" class="w-full border rounded px-3 py-2">
            <option value="monthly">Monthly</option>
            <option value="6 months">6 Months</option>
            <option value="yearly">Yearly</option>
        </select>
        @error('subscription_interval') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Price --}}
    <div>
        <label class="block text-sm font-medium mb-1">Price</label>
        <input type="number" wire:model.defer="price" class="w-full border rounded px-3 py-2" step="0.01" placeholder="Enter price">
        @error('price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Status --}}
    <div class="flex justify-end mt-4">
        <flux:field variant="inline">
            <flux:label>Active</flux:label>
            <flux:switch wire:model.defer="is_active" />
            <flux:error name="is_active" />
        </flux:field>
    </div>

    {{-- Buttons --}}
    <div class="flex justify-end space-x-2 mt-4">
        <flux:button wire:click="save" variant="primary">Save</flux:button>
    </div>
</div>
