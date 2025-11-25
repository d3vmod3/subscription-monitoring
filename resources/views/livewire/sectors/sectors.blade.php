@php 
    use Hashids\Hashids; 
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); 
@endphp

<div class="p-4">
    <div class="mb-2">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
            Sectors
        </h1>
    </div>
    <div class="flex justify-between mb-4">
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search Sectors..." 
            class="border static rounded px-3 py-2 w-1/3"
        >

        <!-- Add Sector Modal Trigger -->
        @can('add sectors')
        <div>
            <flux:modal.trigger name="add-sector" class="flex justify-end">
                <flux:button>Add Sector</flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-sector" class="md:w-96">
                <livewire:sectors.add-sector />
            </flux:modal>
        </div>
        @endcan
    </div>
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('name')">
                        Sector Name
                        @if($sortField == 'name') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('is_active')">
                        Status
                        @if($sortField == 'is_active') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    @can('edit sectors')
                    <th class="px-4 py-2 border">Actions</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @forelse ($sectors as $sector)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                        <td class="px-4 py-2 border font-semibold">{{ $sector->name }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            <span class="{{ $sector->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $sector->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        @can('edit sectors')
                        <td class="px-4 py-2 border text-center">
                            <flux:link href="{{ route('sector.edit', ['hash' => $hashids->encode($sector->id)]) }}">Edit</flux:link>
                        </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-2 text-center text-gray-500">No sectors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sectors->links() }}
    </div>
</div>
