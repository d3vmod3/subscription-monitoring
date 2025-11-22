@php 
    use Hashids\Hashids; 
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); 
@endphp

<div class="p-4 max-w-full">
    <div class="mb-2">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
            Subscribers
        </h1>
    </div>

    {{-- Top controls --}}
    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-3">
        {{-- Search --}}
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search subscribers..." 
            class="border rounded px-3 py-2 w-full sm:w-1/3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >
        @can('add subscribers')
        {{-- Add Subscriber Button --}}
        <flux:link 
            class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150 w-full sm:w-auto text-center"
            href="{{ route('subscribers.add') }}" 
            style="text-decoration: none;"
        >
            Add Subscriber
        </flux:link>
        @endcan
    </div>

    {{-- Table wrapper for horizontal scroll --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('first_name')">
                        Name
                        @if($sortField == 'first_name') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('email')">
                        Email
                        @if($sortField == 'email') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('contact_number')">
                        Contact
                        @if($sortField == 'contact_number') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('status')">
                        Status
                        @if($sortField == 'status') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    @canAny(['view billings', 'edit subscribers']))
                    <th class="px-4 py-2 border whitespace-nowrap text-center">Actions</th>
                    @endcanAny
                </tr>
            </thead>

            <tbody>
                @forelse ($subscribers as $subscriber)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ $subscriber->first_name }} {{ $subscriber->middle_name }} {{ $subscriber->last_name }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $subscriber->email ?? '-' }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $subscriber->contact_number ?? '-' }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            <span class="{{ $subscriber->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $subscriber->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        @canAny(['view billings', 'edit subscribers']))
                        <td class="px-4 py-2 border whitespace-nowrap text-center flex flex-col sm:flex-row justify-center items-center gap-1">
                            @can('view billings')
                            <flux:link 
                                class="px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150"
                                href="{{ route('view-billings', ['hash' => $hashids->encode($subscriber->id)]) }}">
                                View Billings
                            </flux:link>
                            @endcan
                            @can('edit subscribers')
                            <flux:link 
                                class="px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150"
                                href="{{ route('subscribers.edit', ['hash' => $hashids->encode($subscriber->id)]) }}">
                                Edit
                            </flux:link>
                            @endcan
                        </td>
                        @endcanany
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                            No subscribers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $subscribers->links() }}
    </div>
</div>
