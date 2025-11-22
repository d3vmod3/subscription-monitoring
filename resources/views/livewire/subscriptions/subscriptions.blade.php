@php
    use Hashids\Hashids;
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
@endphp

<div class="p-4">
    <div class="mb-2">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
            Subscriptions
        </h1>
    </div>
    <div class="flex justify-between mb-4">
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search subscriptions..." 
            class="border rounded px-3 py-2 w-1/3"
        >

        <div>
            <flux:modal.trigger name="add-subscription">
                <flux:button>Add Subscription</flux:button>
            </flux:modal.trigger>

            <flux:modal name="add-subscription" class="md:w-[32rem]">
                <livewire:subscriptions.add-subscription />
            </flux:modal>
        </div>
    </div>

    <table class="w-full border border-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('mikrotik_name')">
                    Mikrotik Name
                    @if($sortField == 'mikrotik_name') 
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                    @endif
                </th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('subscriber_id')">
                    Subscriber
                </th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('plan_id')">
                    Plan
                </th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('status')">
                    Status
                    @if($sortField == 'status') 
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                    @endif
                </th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($subscriptions as $subscription)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                    <td class="px-4 py-2 border font-semibold">{{ $subscription->mikrotik_name }}</td>
                    <td class="px-4 py-2 border">
                        {{ $subscription->subscriber ? $subscription->subscriber->first_name . ' ' . $subscription->subscriber->last_name : 'N/A' }}
                    </td>
                    <td class="px-4 py-2 border">
                        {{ $subscription->plan->name ?? '-' }}
                    </td>
                    <td class="px-4 py-2 border">
                        <span class="@if($subscription->status === 'active') text-green-600 
                                     @elseif($subscription->status === 'inactive') text-yellow-600 
                                     @else text-red-600 @endif">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border text-center">
                        <flux:link 
                            href="{{ route('subscription.edit', ['hash' => $hashids->encode($subscription->id)]) }}">
                            Edit
                        </flux:link>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                        No subscriptions found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>
</div>
