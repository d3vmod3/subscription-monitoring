@php
    use Hashids\Hashids;
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
@endphp

<div class="p-4">
    <div class="mb-2">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
            Splitters
        </h1>
    </div>
    {{-- Header & Search/Add --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-3 md:space-y-0">
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search Splitters..." 
            class="border rounded px-3 py-2 w-full md:w-1/3 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-zinc-400"
        >
        @can('add splitters')
        <div>
            <flux:modal.trigger name="add-splitter"  class="border">
                <flux:button>
                    Add Splitter
                </flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-splitter" class="md:w-96">
                <livewire:splitters.add-splitter />
            </flux:modal>
        </div>
        @endcan
    </div>

    {{-- Table with horizontal scroll --}}
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 text-zinc-900 dark:text-zinc-100 min-w-[600px]">
            <thead class="bg-gray-50 dark:bg-zinc-600">
                <tr>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('name')">
                        Name
                        @if($sortField == 'name') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('description')">
                        Description
                        @if($sortField == 'description') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('is_active')">
                        Status
                        @if($sortField == 'is_active') @if($sortDirection == 'asc') ▲ @else ▼ @endif @endif
                    </th>
                    @can('edit splitters')
                    <th class="px-4 py-2 border text-center">Actions</th>
                    @endcan
                </tr>
            </thead>

            <tbody>
                @forelse($splitters as $splitter)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700 dark:hover:text-white transition-colors duration-150">
                        <td class="px-4 py-2 border font-semibold">{{ $splitter->name }}</td>
                        <td class="px-4 py-2 border">{{ $splitter->description ?? '-' }}</td>
                        <td class="px-4 py-2 border">
                            <span class="{{ $splitter->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $splitter->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        @can('edit splitters')
                        <td class="px-4 py-2 border text-center">
                            <flux:link href="{{ route('splitter.edit', ['hash' => $splitter->hash]) }}"
                                class="text-zinc-700 dark:text-zinc-100 hover:underline">
                                Edit
                            </flux:link>
                        </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">No splitters found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $splitters->links() }}
    </div>
</div>
