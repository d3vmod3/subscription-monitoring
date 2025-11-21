@php 
    use Hashids\Hashids; 
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); 
@endphp

<div class="p-4 max-w-full overflow-x-hidden">
    <div class="mb-2">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
            Payments
        </h1>
    </div>
    {{-- Top controls --}}
    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-3">
        {{-- 🔍 Search --}}
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search payments (Mikrotik Name or Ref #)..." 
            class="border rounded px-3 py-2 w-full sm:w-1/3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >

        {{-- ➕ Add Payment Button --}}
        @can('add payments')
        <div class="flex-shrink-0">
            <flux:modal.trigger name="add-payment">
                <flux:button>Add Payment</flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-payment" class="w-full">
                <livewire:payments.add-payment />
            </flux:modal>
        </div>
        @endcan
    </div>

    {{-- Pagination --}}
    <div class="mb-4 mt-4 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <label for="">Per Page</label>
            <flux:select class="w-xs" wire:model.live="per_page">
                <flux:select.option>10</flux:select.option>
                <flux:select.option>25</flux:select.option>
                <flux:select.option>50</flux:select.option>
                <flux:select.option>100</flux:select.option>
            </flux:select>
        </div>
        {{ $payments->links() }}
    </div>

    {{-- Table wrapper for horizontal scroll on mobile --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('subscriber_name')">
                        Subscriber
                        @if($sortField == 'subscriber_name') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('subscription_id')">
                        Mikrotik Name
                        @if($sortField == 'subscription_id') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('plan_id')">
                        Plan
                        @if($sortField == 'plan_Id') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('reference_number')">
                        Reference #
                        @if($sortField == 'reference_number') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('payment_method_id')">
                        Payment Method
                        @if($sortField == 'payment_method_id') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('amount')">
                        Amount
                        @if($sortField == 'amount') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('created_at')">
                        Created At
                        @if($sortField == 'created_at') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap">
                        Added By
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('is_approved')">
                        Status
                        @if($sortField == 'is_approved') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    @can('edit payments')
                    <th class="px-4 py-2 border whitespace-nowrap text-center">Actions</th>
                    @endcan
                </tr>
            </thead>

            <tbody>
                @forelse ($payments as $payment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ $payment->subscription->subscriber->full_name ?? $payment->subscription->mikrotik_name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ $payment->subscription->mikrotik_name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ $payment->subscription->plan->name . ' - ' . $payment->subscription->plan->price  ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ $payment->reference_number ?? '—' }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ $payment->paymentMethod->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            ₱{{ number_format($payment->paid_amount, 2) }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ $payment->user->getFullNameAttribute() ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">
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
                        @can('edit payments')
                        <td class="px-4 py-2 border whitespace-nowrap text-center">
                            <flux:link href="{{ route('payment.edit', ['hash' => $hashids->encode($payment->id)]) }}">
                                Edit
                            </flux:link>
                        </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-2 text-center text-gray-500">
                            No payments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <label for="">Per Page</label>
            <flux:select class="w-xs" wire:model.live="per_page">
                <flux:select.option>10</flux:select.option>
                <flux:select.option>25</flux:select.option>
                <flux:select.option>50</flux:select.option>
                <flux:select.option>100</flux:select.option>
            </flux:select>
        </div>
        {{ $payments->links() }}
    </div>
</div>
