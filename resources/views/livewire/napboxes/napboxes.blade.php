@php 
    use Hashids\Hashids; 
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); 
@endphp

<div class="p-4">
    <div class="flex justify-between mb-4">
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search NAP boxes..." 
            class="border rounded px-3 py-2 w-1/3"
        >
        <div>
            <flux:modal.trigger name="add-napbox">
                <flux:button>Add NAP Box</flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-napbox" class="md:w-96">
                <livewire:napboxes.add-napbox/>
            </flux:modal>
        </div>
    </div>

    <table class="w-full border border-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('name')">
                    Napbox Name
                    @if($sortField == 'name') 
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                    @endif
                </th>

                {{-- ✅ Display Parent PON --}}
                <th class="px-4 py-2 border">PON</th>

                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('description')">
                    Description
                    @if($sortField == 'description') 
                        @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                    @endif
                </th>

                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('created_at')">
                    Created At
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
            @forelse ($napboxes as $nap)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                    <td class="px-4 py-2 border font-semibold">{{ $nap->name }}</td>

                    {{-- ✅ Display PON --}}
                    <td class="px-4 py-2 border">{{ $nap->pon->name ?? '-' }}</td>

                    <td class="px-4 py-2 border text-gray-700 dark:text-gray-300">{{ $nap->description ?? '-' }}</td>
                    <td class="px-4 py-2 border text-gray-700 dark:text-gray-300">{{ $nap->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-2 border">
                        <span class="{{ $nap->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $nap->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border text-center">
                        <flux:link href="{{ route('napbox.edit', ['hash' => $hashids->encode($nap->id)]) }}">
                            Edit
                        </flux:link>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-500">No NAP boxes found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $napboxes->links() }}
    </div>
</div>
