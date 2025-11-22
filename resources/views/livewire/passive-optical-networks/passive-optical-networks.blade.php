@php 
    use Hashids\Hashids; 
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); 
@endphp

<div class="p-4 sm:p-6 max-w-full overflow-x-auto">
    <div class="mb-2">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
            PONs (Passive Optical Networks)
        </h1>
    </div>
    {{-- Top Controls --}}
    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-3">
        {{-- 🔍 Search --}}
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search PONs..." 
            class="border rounded px-3 py-2 w-full sm:w-1/3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >

        {{-- ➕ Add PON Button --}}
        @can('add passive optical networks')
        <div class="flex-shrink-0">
            <flux:modal.trigger name="add-pon">
                <flux:button>Add PON</flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-pon" class="w-full sm:max-w-md md:w-96">
                <livewire:passive-optical-networks.add-passive-optical-network/>
            </flux:modal>
        </div>
        @endcan
    </div>

    {{-- Table Wrapper for Horizontal Scroll --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('name')">
                        PON Name
                        @if($sortField == 'name') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('sector_name')">
                        Sector
                        @if($sortField == 'sector_name') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('created_at')">
                        Created At
                        @if($sortField == 'created_at') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('is_active')">
                        Status
                        @if($sortField == 'is_active') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    @can('edit passive optical networks')
                    <th class="px-4 py-2 border whitespace-nowrap text-center">Actions</th>
                    @endcan
                </tr>
            </thead>

            <tbody>
                @forelse ($pons as $pon)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                        <td class="px-4 py-2 border font-semibold whitespace-nowrap">{{ $pon->name }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $pon->sector->name ?? '-' }}</td>
                        <td class="px-4 py-2 border text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $pon->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            <span class="{{ $pon->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $pon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        @can('edit passive optical networks')
                        <td class="px-4 py-2 border text-center whitespace-nowrap">
                            
                            <flux:link href="{{ route('pon.edit', ['hash' => $hashids->encode($pon->id)]) }}">
                                Edit
                            </flux:link>
                        </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">No PONs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $pons->links() }}
    </div>
</div>
