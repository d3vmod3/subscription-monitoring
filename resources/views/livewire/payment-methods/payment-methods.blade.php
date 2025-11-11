@php use Hashids\Hashids; $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); @endphp

<div class="p-4">
    <div class="flex justify-between mb-4">
        <input type="text" wire:model.live="search" placeholder="Search payment methods..." class="border rounded px-3 py-2 w-1/3">
        <div>
            <flux:modal.trigger name="add-payment-method">
                <flux:button>Add Payment Method</flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-payment-method" class="md:w-96">
                <livewire:payment-methods.add-payment-method/>
            </flux:modal>
        </div>
    </div>

    <table class="w-full border border-gray-200">
        <thead>
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
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($paymentMethods as $method)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                    <td class="px-4 py-2 border">{{ $method->name }}</td>
                    <td class="px-4 py-2 border">{{ $method->description ?? '-' }}</td>
                    <td class="px-4 py-2 border">
                        <span class="{{ $method->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border text-center">
                        <flux:link href="{{ route('payment-methods.edit', ['hash' => $hashids->encode($method->id)]) }}">Edit</flux:link>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">No payment methods found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $paymentMethods->links() }}
    </div>
</div>
