@php 
    use Hashids\Hashids; 
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); 
@endphp

<div class="p-4">
    <div class="flex justify-between mb-4">
        {{-- 🔍 Search --}}
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search payments (Mikrotik Name or Ref #)..." 
            class="border rounded px-3 py-2 w-1/3"
        >

        {{-- ➕ Add Payment Button --}}
        <div>
            <flux:modal.trigger name="add-payment">
                <flux:button>Add Payment</flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-payment" class="md:w-[32rem]">
                <livewire:payments.add-payment />
            </flux:modal>
        </div>
    </div>

    <table class="w-full border border-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('subscription_id')">
                    Mikrotik Name
                    @if($sortField == 'subscription_id') 
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                    @endif
                </th>

                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('reference_number')">
                    Reference #
                    @if($sortField == 'reference_number') 
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                    @endif
                </th>

                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('payment_method_id')">
                    Payment Method
                    @if($sortField == 'payment_method_id') 
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                    @endif
                </th>

                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('amount')">
                    Amount
                    @if($sortField == 'amount') 
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                    @endif
                </th>

                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('paid_at')">
                    Paid At
                    @if($sortField == 'paid_at') 
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                    @endif
                </th>

                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('is_approved')">
                    Status
                    @if($sortField == 'is_approved') 
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                    @endif
                </th>

                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($payments as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                    <td class="px-4 py-2 border">
                        {{ $payment->subscription->mikrotik_name ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-2 border">
                        {{ $payment->reference_number ?? '—' }}
                    </td>
                    <td class="px-4 py-2 border">
                        {{ $payment->paymentMethod->name ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-2 border">
                        ₱{{ number_format($payment->amount, 2) }}
                    </td>
                    <td class="px-4 py-2 border">
                        {{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d') }}
                    </td>
                    <td class="px-4 py-2 border">
                        @php
                            $statusClasses = [
                                'Pending' => 'text-yellow-600',
                                'Approved' => 'text-green-600',
                                'Disapproved' => 'text-red-600',
                            ];
                        @endphp
                        <span class="{{ $statusClasses[$payment->status] ?? 'text-gray-600' }}">
                            {{ $payment->status ?? 'Pending' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border text-center">
                        <flux:link href="{{ route('payment.edit', ['hash' => $hashids->encode($payment->id)]) }}">
                            Edit
                        </flux:link>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                        No payments found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>
