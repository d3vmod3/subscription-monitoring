<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-700 rounded-lg shadow space-y-6 dark:shadow-lg">

    {{-- 🧾 Title --}}
    <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-zinc-900 dark:text-zinc-100 text-center sm:text-left">
        Update Payment Status
    </h2>

    {{-- 👤 Subscriber --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Subscriber</label>
        <input type="text"
            class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
            value="{{ $selectedSubscription->subscriber->full_name ?? 'Unnamed Subscriber' }}" readonly>
    </div>

    {{-- 📦 Current Plan --}}
    @if($selectedSubscription && $selectedSubscription->plan)
        Subscription Details
        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded border space-y-1">
            <p class="text-sm sm:text-base"><strong>Mikrotik Name:</strong> {{ $selectedSubscription->mikrotik_name }}</p>
            <p class="text-sm sm:text-base"><strong>Current Plan:</strong> {{ $selectedSubscription->plan->name }}</p>
            <p class="text-sm sm:text-base"><strong>Price:</strong> ₱{{ number_format($selectedSubscription->plan->price, 2) }}</p>
            <p class="text-sm sm:text-base text-gray-700 dark:text-gray-200">
                <strong>Total Paid for {{ $month_year_cover }}:</strong> ₱{{ number_format($total_paid, 2) }}
            </p>
            @if ($expected_amount)
                <p class="text-sm sm:text-base text-blue-600 dark:text-blue-400">
                    <strong>Expected for selected month:</strong> ₱{{ number_format($expected_amount, 2) }}
                </p>
            @else
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400">
                    <strong>Expected for selected month:</strong> ₱{{ number_format($expected_amount, 2) }}
                    <br>
                    <i class="text-orange-600">Payment is already made for this month {{ \Carbon\Carbon::parse($month_year_cover)->format('F Y') }}</i>
                </p>
            @endif

        </div>
        
    @endif

    {{-- 💳 Payment Info (readonly) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Account Name</label>
            <input type="text"
                class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
                value="{{ $payment->account_name }}" readonly>
        </div>

        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Amount Paid</label>
            <input type="text"
                class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
                value="₱{{ number_format($payment->paid_amount, 2) }}" readonly>
        </div>
    </div>

    {{-- 💰 Payment Method & Date --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Payment Method</label>
            <input type="text"
                class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
                value="{{ $payment->paymentMethod->name }}" readonly>
        </div>
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Reference Number</label>
            <input type="text"
                class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
                value="{{ $payment->reference }}" readonly>
        </div>

        
    </div>
    {{-- Paid at  }}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Paid At</label>
        <input type="date"
            class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
            value="{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d') }}" readonly>
    </div>

    {{-- 🗓️ Month Covered --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Month Covered</label>
        <input type="month"
            class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
            value="{{ $month_year_cover }}" readonly>
    </div>

    {{-- Discount--}}
    <div class="flex justify-between space-x-8 items-center mt-4">
        <div class="w-full">
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Discount</label>
            <input type="number" step="0.01" wire:model.defer="discount_amount" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Enter discount amount" readonly>
        </div>
    </div>
    @error('discount_amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

    {{-- Remarks --}}
    <div>
        <label class="block text-sm font-medium mb-1">Remarks</label>
        <textarea wire:model.defer="remarks" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" rows="3" readonly></textarea>
    </div>

    {{-- ⚙️ Status (editable only) --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Status</label>
        <select wire:model.defer="status"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 
                   border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="Approved">Approved</option>
            <option value="Disapproved">Disapproved</option>
            <option value="Pending">Pending</option>
        </select>
        @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- 💾 Buttons --}}
    <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4">
        <flux:button wire:click="save" wire:loading.attr="disabled" variant="primary">
            <span wire:loading.remove>Update Status</span>
            <span wire:loading>Updating...</span>
        </flux:button>

        <flux:link href="{{ route('payments') }}" variant="secondary"
            class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150 w-full sm:w-auto text-center"
            style="text-decoration: none;">
            Back to Payments
        </flux:link>
        @endcan
    </div>

</div>
