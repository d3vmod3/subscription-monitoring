<div class="space-y-6 p-4 w-full mx-auto">
    <h2 class="text-xl sm:text-2xl font-semibold mb-4 text-center sm:text-left">Add New Payment</h2>

    {{-- Subscription (Searchable by mikrotik_name) --}}
    <div class="relative">
        <label class="block text-sm font-medium mb-1">Mikrotik Name</label>
        <input 
            type="text" 
            wire:model.live="subscriber_search" 
            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Search subscription by Mikrotik name..."
        >
        @if(!empty($subscriber_results))
            <ul class="absolute z-10 bg-gray-100 dark:bg-gray-700 border rounded mt-1 w-full shadow-md max-h-48 overflow-y-auto">
                @foreach($subscriber_results as $sub)
                    <li 
                        wire:click="selectSubscriber({{ $sub->id }}, '{{ $sub->mikrotik_name }}')" 
                        class="px-3 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer"
                    >
                        {{ $sub->display_name }}
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

    {{-- Display current plan --}}
    @if ($subscription_id && $selectedSubscription && $selectedSubscription->plan)
        <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-700 rounded border space-y-1">
            <p class="text-sm sm:text-base text-gray-700 dark:text-gray-200">
                <strong>Current Plan:</strong> {{ $selectedSubscription->plan->name ?? 'No Plan' }}
            </p>
            <p class="text-sm sm:text-base text-gray-700 dark:text-gray-200">
                <strong>Price:</strong> ₱{{ number_format($selectedSubscription->plan->price ?? 0, 2) }}
            </p>
            @if ($expected_amount)
                <p class="text-sm sm:text-base text-blue-600 dark:text-blue-400">
                    <strong>Expected for selected month:</strong> ₱{{ number_format($expected_amount, 2) }}
                </p>
            @endif
        </div>
    @endif

    {{-- Payment Method --}}
    <div>
        <label class="block text-sm font-medium mb-1">Payment Method</label>
        <select wire:model.defer="payment_method_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="">-- Select Payment Method --</option>
            @foreach ($payment_methods as $method)
                <option value="{{ $method->id }}">{{ $method->name }}</option>
            @endforeach
        </select>
        @error('payment_method_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Account Name & Reference Number in flex for larger screens --}}
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2  xl:grid-cols-2 2xl:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Account Name</label>
            <input type="text" wire:model="account_name" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Enter account name (required)">
            @error('account_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Reference Number</label>
            <input type="text" wire:model.defer="reference_number" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Optional reference number">
            @error('reference_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Paid At & Month-Year Cover in flex for larger screens --}}
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2  xl:grid-cols-2 2xl:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Paid At</label>
            <input type="date" wire:model.defer="paid_at" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('paid_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Month Covered</label>
            <input type="month" wire:model.live="month_year_cover" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('month_year_cover') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Paid Amount --}}
    <div>
        <label class="block text-sm font-medium mb-1">Amount Paid</label>
        <input type="number" step="0.01" wire:model.defer="paid_amount" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Enter amount paid">
        @error('paid_amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    
        <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
            Expected amount: ₱{{ number_format($expected_amount, 2) }}
        </p>
    
    </div>

    {{-- Discounted Switch --}}
    <div class="flex justify-between space-x-8 items-center mt-4">
        <flux:field variant="inline" class="flex items-center space-x-2">
            <flux:label>Discounted</flux:label>
            <flux:switch wire:model.live="is_discounted" />
            <flux:error name="is_discounted" />
        </flux:field>
        @if($is_discounted)
        <div class="w-full">
            <input type="number" step="0.01" wire:model.defer="discount_amount" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Enter discount amount">
            
        </div>
        @endif
    </div>
    @error('discount_amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    

    {{-- Remarks --}}
    <div>
        <label class="block text-sm font-medium mb-1">Remarks</label>
        <textarea wire:model.defer="remarks" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" rows="3" placeholder="Enter discount reason or notes"></textarea>
        @error('remarks') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
    
    {{-- Buttons --}}
    <div class="flex justify-end space-x-2 mt-4">
        <flux:button wire:click="save" variant="primary">Save</flux:button>
    </div>
</div>
