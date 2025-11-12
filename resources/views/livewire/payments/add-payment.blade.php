<div class="space-y-6 p-4">
    <h2 class="text-lg font-semibold mb-4">Add New Payment</h2>

    {{-- Subscription (Searchable by mikrotik_name) --}}
    <div class="relative">
        <label class="block text-sm font-medium mb-1">Mikrotik Name</label>
        <input 
            type="text" 
            wire:model.live="subscriber_search" 
            class="w-full border rounded px-3 py-2" 
            placeholder="Search subscription by Mikrotik name..."
        >
        @if(!empty($subscriber_results))
            <ul class="absolute z-10 bg-gray-100 dark:bg-gray-600 border rounded mt-1 w-full shadow-md max-h-48 overflow-y-auto">
                @foreach($subscriber_results as $sub)
                    <li 
                        wire:click="selectSubscriber({{ $sub->id }}, '{{ $sub->mikrotik_name }}')" 
                        class="px-3 py-2 hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                    >
                        {{ $sub->mikrotik_name }} 
                        <span class="text-sm text-gray-600 dark:text-gray-300">
                            ({{ $sub->plan->name ?? 'No Plan' }})
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
        @error('subscription_id') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Payment Method --}}
    <div>
        <label class="block text-sm font-medium mb-1">Payment Method</label>
        <select wire:model.defer="payment_method_id" class="w-full border rounded px-3 py-2">
            <option value="">-- Select Payment Method --</option>
            @foreach ($payment_methods as $method)
                <option value="{{ $method->id }}">{{ $method->name }}</option>
            @endforeach
        </select>
        @error('payment_method_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Account Name --}}
    <div>
        <label class="block text-sm font-medium mb-1">Account Name</label>
        <input type="text" wire:model="account_name" class="w-full border rounded px-3 py-2" placeholder="Enter account name (required)">
        @error('account_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Reference Number --}}
    <div>
        <label class="block text-sm font-medium mb-1">Reference Number</label>
        <input type="text" wire:model.defer="reference_number" class="w-full border rounded px-3 py-2" placeholder="Optional reference number">
        @error('reference_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Paid At --}}
    <div>
        <label class="block text-sm font-medium mb-1">Paid At</label>
        <input type="date" wire:model.defer="paid_at" class="w-full border rounded px-3 py-2">
        @error('paid_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Date Coverage --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Date Cover From</label>
            <input type="date" wire:model.defer="date_cover_from" class="w-full border rounded px-3 py-2">
            @error('date_cover_from') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Date Cover To</label>
            <input type="date" wire:model.defer="date_cover_to" class="w-full border rounded px-3 py-2">
            @error('date_cover_to') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Amount --}}
    <div>
        <label class="block text-sm font-medium mb-1">Amount</label>
        <input type="number" step="0.01" wire:model.defer="amount" class="w-full border rounded px-3 py-2" placeholder="Enter amount paid">
        @error('amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Discounted + Approved Switches --}}
    <div class="flex flex-col sm:flex-row justify-between mt-4 space-y-3 sm:space-y-0 sm:space-x-4">
        <flux:field variant="inline">
            <flux:label>Discounted</flux:label>
            <flux:switch wire:model.live="is_discounted" />
            <flux:error name="is_discounted" />
        </flux:field>
    </div>

    {{-- Remarks (only for discounted payments) --}}
    @if ($is_discounted)
        <div>
            <label class="block text-sm font-medium mb-1">Remarks (for discounted payments)</label>
            <textarea wire:model.defer="remarks" class="w-full border rounded px-3 py-2" rows="3" placeholder="Enter discount reason or notes"></textarea>
            @error('remarks') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    @endif

    {{-- Buttons --}}
    <div class="flex justify-end space-x-2 mt-4">
        <flux:button wire:click="save" variant="primary">Save</flux:button>
    </div>
</div>
