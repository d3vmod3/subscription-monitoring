@php use Hashids\Hashids; $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); @endphp

<div class="p-4">
    <div class="flex justify-between mb-4">
        <input type="text" wire:model.live="search" placeholder="Search subscribers..." class="border rounded px-3 py-2 w-1/3">
        <flux:link class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150" href="{{ route('subscribers.add') }}" style="text-decoration: none;">Add Subscriber</flux:link>
    </div>

    <table class="w-full border border-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('first_name')">
                    Name
                    @if($sortField == 'first_name') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                </th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('email')">
                    Email
                    @if($sortField == 'email') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                </th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('contact_number')">
                    Contact
                    @if($sortField == 'contact_number') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                </th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('status')">
                    Status
                    @if($sortField == 'status') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                </th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($subscribers as $subscriber)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                    <td class="px-4 py-2 border">{{ $subscriber->first_name }} {{ $subscriber->middle_name }} {{ $subscriber->last_name }}</td>
                    <td class="px-4 py-2 border">{{ $subscriber->email ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $subscriber->contact_number ?? '-' }}</td>
                    <td class="px-4 py-2 border">
                        <span class="{{ $subscriber->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $subscriber->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border">
                        <flux:link href="{{ route('subscribers.edit', ['hash' => $hashids->encode($subscriber->id)]) }}">Edit</flux:link>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-500">No subscribers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $subscribers->links() }}
    </div>
</div>
