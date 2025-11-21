@php 
    use Hashids\Hashids; 
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); 
@endphp

<div class="p-4 max-w-full">
    <div class="mb-2">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
            Users
        </h1>
    </div>

    {{-- Top controls --}}
    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-3">
        {{-- Search --}}
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search users..." 
            class="border rounded px-3 py-2 w-full sm:w-1/3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >

        {{-- Add User Button --}}
        <flux:link 
            class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150 w-full sm:w-auto text-center"
            href="{{ route('user.add') }}" 
            style="text-decoration: none;"
        >
            Add User
        </flux:link>
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
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('is_active')">
                        Status
                        @if($sortField == 'is_active') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border whitespace-nowrap text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $user->email ?? '-' }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $user->contact_number ?? '-' }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            <span class="{{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap text-center flex flex-col sm:flex-row justify-center items-center gap-1">
                            <flux:link 
                                class="px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150"
                                href="{{ route('user.edit', ['hash' => $hashids->encode($user->id)]) }}">
                                Edit
                            </flux:link>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
