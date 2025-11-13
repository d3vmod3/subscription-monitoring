<div class="p-4 max-w-4xl mx-auto space-y-4">
    <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
        Subscriber Billings: {{ $subscriber->full_name }}
    </h2>

    {{-- Select Subscription --}}
    @if ($subscriptions->count())
        <div>
            <label class="block mb-1 font-medium text-zinc-900 dark:text-zinc-100">Choose Mikrotik Name</label>
            <select wire:model.live="subscriptionHash"
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
                <option value="">-- Select Subscription --</option>
                @php $hashids = new \Hashids\Hashids(config('hashids.salt'), config('hashids.min_length')); @endphp
                @foreach ($subscriptions as $sub)
                    <option value="{{ $hashids->encode($sub->id) }}">
                        {{ $sub->mikrotik_name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    {{-- Month Cover Range --}}
    @if ($selectedSubscription)
        <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block mb-1 font-medium text-zinc-900 dark:text-zinc-100">Start Date</label>
                <input type="date"
                    value="{{ \Carbon\Carbon::parse($selectedSubscription->start_date)->format('Y-m-d') }}"
                    class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
                    disabled>
            </div>
            <div>
                <label class="block mb-1 font-medium text-zinc-900 dark:text-zinc-100">Month Cover From</label>
                <input type="month" wire:model.live="month_cover_from"
                    class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            </div>
            <div>
                <label class="block mb-1 font-medium text-zinc-900 dark:text-zinc-100">Month Cover To</label>
                <input type="month" wire:model.live="month_cover_to"
                    class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            </div>
            <div class="sm:col-span-3 mt-2">
                <flux:button wire:click="filterPayments">Calculate Bills</flux:button>
            </div>
        </div>

        {{-- Totals --}}
        <div class="mt-4 p-4 border rounded bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 space-y-1">
            <div><span class="font-semibold">Expected Total Amount:</span>
                <span class="text-green-600 font-bold">₱{{ number_format($expectedTotal, 2) }}</span>
            </div>
            <div><span class="font-semibold">Total Paid:</span>
                <span class="text-blue-600 font-bold">₱{{ number_format($totalPaid, 2) }}</span>
            </div>
        </div>

        {{-- Payments Table --}}
        @if ($payments && $payments->count())
            <h3 class="mt-6 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Payments</h3>
            <table class="w-full border mt-2 text-zinc-900 dark:text-zinc-100">
                <thead>
                    <tr class="bg-gray-100 dark:bg-zinc-700">
                        <th class="px-4 py-2 border">Month Covered</th>
                        <th class="px-4 py-2 border">Amount Paid</th>
                        <th class="px-4 py-2 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-600 transition-colors duration-150">
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($payment->month_year_cover . '-01')->format('F Y') }}
                            </td>
                            <td class="px-4 py-2 border text-green-600 font-semibold">
                                ₱{{ number_format($payment->paid_amount, 2) }}
                            </td>
                            <td class="px-4 py-2 border">{{ $payment->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500 dark:text-zinc-400 mt-4">No payments found for this range.</p>
        @endif

        {{-- Billing Summary --}}
        @if ($billingSummary && $billingSummary->count())
            <h3 class="mt-8 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Billing Status Summary</h3>
            <table class="w-full border mt-2 text-zinc-900 dark:text-zinc-100">
                <thead>
                    <tr class="bg-gray-100 dark:bg-zinc-700">
                        <th class="px-4 py-2 border">Month (Year)</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Remaining Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($billingSummary as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-600 transition-colors duration-150">
                            <td class="px-4 py-2 border">{{ $row['month'] }}</td>
                            <td class="px-4 py-2 border font-semibold {{ $row['status'] === 'Paid' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $row['status'] }}
                            </td>
                            <td class="px-4 py-2 border text-red-600 font-semibold">
                                @if(!empty($row['remaining_balance']))
                                    ₱{{ number_format($row['remaining_balance'], 2) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif
</div>
