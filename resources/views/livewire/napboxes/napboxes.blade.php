@php 
    use Hashids\Hashids; 
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); 
@endphp

<div class="p-4 max-w-full overflow-x-hidden">
    {{-- 🔍 Top Controls --}}
    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-3">
        {{-- Search --}}
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search NAP boxes..." 
            class="border rounded px-3 py-2 w-full sm:w-1/3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >

        {{-- ➕ Add Napbox Button --}}
        <div class="flex-shrink-0">
            <flux:modal.trigger name="add-napbox">
                <flux:button>Add NAP Box</flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-napbox" class="w-full sm:max-w-md md:w-96">
                <livewire:napboxes.add-napbox />
            </flux:modal>
        </div>
    </div>

    {{-- 🧾 Table Wrapper for horizontal scroll on mobile --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('name')">
                        Napbox Name
                        @if($sortField == 'name') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>

                    <th class="px-4 py-2 border whitespace-nowrap">PON</th>

                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('description')">
                        Description
                        @if($sortField == 'description') 
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

                    <th class="px-4 py-2 border whitespace-nowrap text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($napboxes as $nap)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                        <td class="px-4 py-2 border whitespace-nowrap font-semibold">{{ $nap->name }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $nap->pon->name ?? '-' }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $nap->description ?? '-' }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $nap->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            <span class="{{ $nap->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $nap->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap text-center">
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
    </div>

    {{-- 📄 Pagination --}}
    <div class="mt-4">
        {{ $napboxes->links() }}
    </div>
</div>
