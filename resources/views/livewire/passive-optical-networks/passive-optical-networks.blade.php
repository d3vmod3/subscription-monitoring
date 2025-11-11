@php use Hashids\Hashids; $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); @endphp
<div class="p-4">
    <div class="flex justify-between mb-4">
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search PONs..." 
            class="border rounded px-3 py-2 w-1/3"
        >
        <div>
            <flux:modal.trigger name="add-pon">
                <flux:button>Add PON</flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-pon" class="md:w-96">
                <livewire:passive-optical-networks.add-passive-optical-network/>
            </flux:modal>
        </div>
    </div>

    <table class="w-full border border-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('name')">
                    PON Name
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
            @forelse ($pons as $pon)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                    <td class="px-4 py-2 border font-semibold">{{ $pon->name }}</td>
                    <td class="px-4 py-2 border font-semibold">{{ $pon->description }}</td>
                    <td class="px-4 py-2 border text-gray-700 dark:text-gray-300">{{ $pon->description ?? '-' }}</td>
                    <td class="px-4 py-2 border">
                        <span class="{{ $pon->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $pon->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border text-center">
                        <flux:link href="{{ route('pon.edit', ['hash' => $hashids->encode($pon->id)]) }}">Edit</flux:link>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">No PONs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $pons->links() }}
    </div>
</div>