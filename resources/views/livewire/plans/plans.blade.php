@php
    use Hashids\Hashids;
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
@endphp

<div class="p-4">

    {{-- Search + Add --}}
    <div class="flex flex-col md:flex-row justify-between mb-4 gap-2 md:gap-0">
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search plans..." 
            class="border rounded px-3 py-2 w-full md:w-1/3"
        >
        <div class="flex justify-start md:justify-end">
            <flux:modal.trigger name="add-plan">
                <flux:button>Add Plan</flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-plan" class="md:w-96">
                <livewire:plans.add-plan/>
            </flux:modal>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 min-w-[700px]">
            <thead>
                <tr>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('name')">
                        Name
                        @if($sortField == 'name') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('description')">
                        Description
                        @if($sortField == 'description') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('price')">
                        Price
                        @if($sortField == 'price') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('subscription_interval')">
                        Interval
                        @if($sortField == 'subscription_interval') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('created_at')">
                        Date Created
                        @if($sortField == 'created_at') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('is_active')">
                        Status
                        @if($sortField == 'is_active') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($plans as $plan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                        <td class="px-4 py-2 border">{{ $plan->name }}</td>
                        <td class="px-4 py-2 border">{{ $plan->description ?? '-' }}</td>
                        <td class="px-4 py-2 border">₱{{ number_format($plan->price, 2) }}</td>
                        <td class="px-4 py-2 border">{{ ucfirst($plan->subscription_interval) }}</td>
                        <td class="px-4 py-2 text-center border">{{ $plan->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 border">
                            <span class="{{ $plan->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border text-center">
                            <flux:link href="{{ route('plan.edit', ['hash' => $hashids->encode($plan->id)]) }}">Edit</flux:link>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-gray-500">No plans found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $plans->links() }}
    </div>
</div>
