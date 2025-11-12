<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-700 rounded-lg shadow space-y-6 dark:shadow-lg">

    <h2 class="text-2xl font-bold mb-6 text-zinc-900 dark:text-zinc-100">Edit Payment Status</h2>

    {{-- Subscriber --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Subscriber</label>
        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600" 
            value="{{ $payment->subscription->subscriber->full_name ?? 'Unnamed Subscriber' }}" readonly>
    </div>

    {{-- Account Name --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Account Name</label>
        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600" 
            value="{{ $payment->account_name }}" readonly>
    </div>

    {{-- Payment Method --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Payment Method</label>
        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600" 
            value="{{ $payment->paymentMethod->name }}" readonly>
    </div>

    {{-- Amount --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Amount</label>
        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600" 
            value="{{ number_format($payment->amount, 2) }}" readonly>
    </div>

    {{-- Paid At --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Paid At</label>
        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600" 
            value="{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d') }}" readonly>
    </div>

    {{-- Date Coverage --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Date Cover From</label>
            <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600" 
                value="{{ $payment->date_cover_from }}" readonly>
        </div>
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Date Cover To</label>
            <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600" 
                value="{{ $payment->date_cover_to }}" readonly>
        </div>
    </div>

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
            <span wire:loading.remove>Update Status</span>
            <span wire:loading>Updating...</span>
        </flux:button>
        <flux:link href="{{ route('payments') }}" variant="secondary" 
            class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150" 
            style="text-decoration: none;">Payments List</flux:link>
    </div>
</div>
