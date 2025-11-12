<div class="p-4 max-w-4xl mx-auto space-y-4">
    <h2 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Subscriber Billings: {{ $subscriber->full_name }}</h2>

    {{-- Select Subscription (Mikrotik Name) --}}
    @if($subscriptions)
        <div>
            <label class="block mb-1 font-medium text-zinc-900 dark:text-zinc-100">Choose Mikrotik Name</label>
            <select wire:model.live="subscriptionHash" class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
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

    {{-- Date Range and Payments --}}
        @if($selectedSubscription)
            <div class="mt-4 p-4 border rounded bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100">
            <span class="font-semibold">Expected Total Amount for {{ $year }}:</span>
            <span class="text-green-600 font-bold">₱{{ number_format($expectedTotal, 2) }}</span>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block mb-1 font-medium text-zinc-900 dark:text-zinc-100">Date Cover From</label>
                <input type="date" value="{{ $selectedSubscription->date_start }}" wire:model.live="date_cover_from" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            </div>
            <div>
                <label class="block mb-1 font-medium text-zinc-900 dark:text-zinc-100">Date Cover To</label>
                <input type="date" value="{{ $selectedSubscription->date_end ?? '' }}" wire:model.live="date_cover_to" class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            </div>
            <div>
                <flux:button>Filter</flux:button>
            </div>
        </div>

        {{-- Payments Table --}}
        @if($payments && $payments->count())
            <table class="w-full border mt-4 text-zinc-900 dark:text-zinc-100">
                <thead>
                    <tr class="bg-gray-100 dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100">
                        <th class="px-4 py-2 border">Month</th>
                        <th class="px-4 py-2 border">Date Cover From</th>
                        <th class="px-4 py-2 border">Date Cover To</th>
                        <th class="px-4 py-2 border">Amount Paid</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $months = collect([
                            'January','February','March','April','May','June','July','August','September','October','November','December'
                        ]);
                    @endphp

                    @foreach($months as $index => $month)
                        @php
                            $monthPayments = $payments->filter(function($payment) use ($index) {
                                return \Carbon\Carbon::parse($payment->date_cover_from)->month == $index + 1;
                            });
                        @endphp

                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-600 transition-colors duration-150">
                            <td class="px-4 py-2 border font-medium">{{ $month }}</td>
                            @if($monthPayments->count())
                                @php $p = $monthPayments->first(); @endphp
                                <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($p->date_cover_from)->format('Y-m-d') }}</td>
                                <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($p->date_cover_to)->format('Y-m-d') }}</td>
                                <td class="px-4 py-2 border text-green-600 font-semibold">₱{{ number_format($p->amount, 2) }}</td>
                            @else
                                <td class="px-4 py-2 border text-center text-gray-500 dark:text-zinc-400" colspan="3">No payment</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif($selectedSubscription)
            <p class="text-gray-500 dark:text-zinc-400 mt-2">No payments found for <span class="font-semibold">{{ $selectedSubscription->mikrotik_name }}</span> in {{ $year }}.</p>
        @endif
    @endif
</div>
