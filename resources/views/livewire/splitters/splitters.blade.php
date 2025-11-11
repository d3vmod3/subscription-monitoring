@php
    use Hashids\Hashids;
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
@endphp

<div class="p-4">
    <div class="flex justify-between mb-4">
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search Splitters..." 
            class="border rounded px-3 py-2 w-1/3"
        >
        <div>
            <flux:modal.trigger name="add-splitter">
                <flux:button>Add Splitter</flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-splitter" class="md:w-96">
                <livewire:splitters.add-splitter />
            </flux:modal>
        </div>
    </div>

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
            @forelse($splitters as $splitter)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                    <td class="px-4 py-2 border font-semibold">{{ $splitter->name }}</td>
                    <td class="px-4 py-2 border">{{ $splitter->description ?? '-' }}</td>
                    <td class="px-4 py-2 border">
                        <span class="{{ $splitter->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $splitter->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border text-center">
                        <flux:link href="{{ route('splitter.edit', ['hash' => $splitter->hash]) }}">Edit</flux:link>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">No splitters found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $splitters->links() }}
    </div>
</div>
