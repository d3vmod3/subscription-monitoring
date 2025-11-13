<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-700 rounded-lg shadow space-y-6 dark:shadow-lg">

    <h2 class="text-2xl font-bold mb-6 text-zinc-900 dark:text-zinc-100">Edit Payment</h2>

    {{-- Subscriber --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Subscriber</label>
        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600" 
            value="{{ $selectedSubscription->subscriber->full_name ?? 'Unnamed Subscriber' }}" readonly>
    </div>

    {{-- Current Plan --}}
    @if($selectedSubscription && $selectedSubscription->plan)
        <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded border">
            <p><strong>Current Plan:</strong> {{ $selectedSubscription->plan->name }}</p>
            <p><strong>Price:</strong> ₱{{ number_format($selectedSubscription->plan->price, 2) }}</p>
            <p class="text-sm text-gray-700 dark:text-gray-200">
                <strong>Total Paid for {{ $month_year_cover }}:</strong> ₱{{ number_format($total_paid, 2) }}
            </p>
        </div>
    @endif

    {{-- Account Name --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Account Name</label>
        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600" 
            wire:model.defer="account_name">
        @error('account_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Payment Method --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Payment Method</label>
        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600" 
            value="{{ $payment->paymentMethod->name }}" readonly>
    </div>

    {{-- Paid Amount --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Amount Paid</label>
        <input type="number" step="0.01" wire:model.defer="paid_amount" class="w-full border rounded px-3 py-2" placeholder="Enter amount paid">
        @error('paid_amount') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Paid At --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Paid At</label>
        <input type="date" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600" 
            value="{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d') }}" readonly>
    </div>

    {{-- Month-Year Covered --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Month Covered</label>
        <input type="month" wire:model.defer="month_year_cover" class="w-full border rounded px-3 py-2">
        @error('month_year_cover') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Discounted --}}
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
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Remarks (for discounted payments)</label>
            <textarea wire:model.defer="remarks" class="w-full border rounded px-3 py-2" rows="3" placeholder="Enter discount reason or notes"></textarea>
            @error('remarks') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    @endif

    {{-- Status --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Status</label>
        <select wire:model.defer="status"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Disapproved">Disapproved</option>
        </select>
        @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Buttons --}}
    <div class="flex justify-end space-x-2 mt-4">
        <flux:button wire:click="save" wire:loading.attr="disabled" variant="primary">
            <span wire:loading.remove>Update Payment</span>
            <span wire:loading>Updating...</span>
        </flux:button>
        <flux:link href="{{ route('payments') }}" variant="secondary" 
            class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150" 
            style="text-decoration: none;">Payments List</flux:link>
    </div>
</div>
