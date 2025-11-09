@php
    use Hashids\Hashids;
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
@endphp

<div class="p-4">
    <div class="flex justify-between mb-4">
        {{-- Search --}}
        <input type="text" wire:model.live="search" placeholder="Search sectors..." 
            class="border rounded px-3 py-2 w-1/3">

        {{-- Add Button --}}
        <flux:link 
            class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150" 
            href="{{ route('sectors.add') }}" 
            style="text-decoration: none;"
        >
            Add Sector
        </flux:link>
    </div>

    {{-- Table --}}
    <table class="w-full border border-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('name')">
                    Name
                    @if($sortField == 'name')
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif
                    @endif
                </th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('description')">
                    Description
                    @if($sortField == 'description')
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif
                    @endif
                </th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('created_at')">
                    Date Created
                    @if($sortField == 'created_at')
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif
                    @endif
                </th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('is_active')">
                    Status
                    @if($sortField == 'is_active')
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif
                    @endif
                </th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($sectors as $sector)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                    <td class="px-4 py-2 border">{{ $sector->name }}</td>
                    <td class="px-4 py-2 border">{{ $sector->description ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $sector->created_at ?? '-' }}</td>
                    <td class="px-4 py-2 border">
                        <span class="{{ $sector->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $sector->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border text-center">
                        <flux:link 
                            href="{{ route('sectors.edit', ['hash' => $hashids->encode($sector->id)]) }}"
                            class="hover:underline"
                        >
                            Edit
                        </flux:link>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                        No sectors found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $sectors->links() }}
    </div>
</div>
