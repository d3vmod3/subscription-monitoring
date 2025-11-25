@php 
    use Hashids\Hashids; 
    $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length')); 
@endphp

<div class="p-4">
    <div class="mb-2">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
            Expenses
        </h1>
    </div>
    <div class="flex justify-between mb-4">
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search expenses..." 
            class="border static rounded px-3 py-2 w-1/3"
        >

        <!-- Add expense Modal Trigger -->
        @can('add expenses')
        <div>
            <flux:modal.trigger name="add-expense" class="flex justify-end">
                <flux:button>Add Expense</flux:button>
            </flux:modal.trigger>
            <flux:modal name="add-expense" class="md:w-96">
                <livewire:expenses.add-expense />
            </flux:modal>
        </div>
        @endcan
    </div>
    <div class="mb-4 mt-4 flex flex-col sm:flex-col md:flex-col lg:flex-row xl:flex-row 2xl:flex-row items-center justify-between">
        <div class="flex items-center space-x-2">
            <label for="">Per Page</label>
            <flux:select class="w-none sm:w-none md:w-none lg:w-xs xl:w-xs 2xl:w-xs" wire:model.live="per_page">
                <flux:select.option>10</flux:select.option>
                <flux:select.option>25</flux:select.option>
                <flux:select.option>50</flux:select.option>
                <flux:select.option>100</flux:select.option>
            </flux:select>
        </div>
        {{ $expenses->links() }}
    </div>
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('title')">
                        Title
                        @if($sortField == 'title') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('description')">
                        Description
                        @if($sortField == 'title') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('status')">
                        Status
                        @if($sortField == 'status') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('users.first_name')">
                        Added By
                        @if($sortField == 'users.first_name') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer whitespace-nowrap" wire:click="sortBy('amount')">
                        Total Amount
                        @if($sortField == 'amount') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('created_at')">
                        Date and Time Created
                        @if($sortField == 'created_at') 
                            @if($sortDirection == 'asc') ▲ @else ▼ @endif 
                        @endif
                    </th>
                    @can('edit expenses')
                    <th class="px-4 py-2 border">Actions</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @forelse ($expenses as $expense)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150">
                        <td class="px-4 py-2 border font-semibold max-w-[200px]">{{ $expense->title }}</td>
                        <td class="px-4 py-2 border font-semibold truncate max-w-[400px]">{{ $expense->description }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            @php
                                $statusClasses = [
                                    'Pending' => 'text-yellow-600',
                                    'Approved' => 'text-green-600',
                                    'Disapproved' => 'text-red-600',
                                ];
                            @endphp
                            <span class="{{ $statusClasses[$expense->status] ?? 'text-gray-600' }}">
                                {{ $expense->status ?? 'Pending' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ $expense->user->getFullNameAttribute() ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            ₱{{ number_format($expense->amount, 2) }}
                        </td>
                        <td class="px-4 py-2 border font-semibold truncate max-w-[400px]">
                            {{ \Carbon\Carbon::parse($expense->created_at)->format('Y-m-d h:i A') }}
                        </td>
                        @can('edit expenses')
                        <td class="px-4 py-2 border text-center">
                            <flux:link href="{{ route('expense.edit', ['hash' => $hashids->encode($expense->id)]) }}">Edit</flux:link>
                        </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-2 text-center text-gray-500">No expenses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mb-4 mt-4 flex flex-col sm:flex-col md:flex-col lg:flex-row xl:flex-row 2xl:flex-row items-center justify-between">
        <div class="flex items-center space-x-2">
            <label for="">Per Page</label>
            <flux:select class="w-none sm:w-none md:w-none lg:w-xs xl:w-xs 2xl:w-xs" wire:model.live="per_page">
                <flux:select.option>10</flux:select.option>
                <flux:select.option>25</flux:select.option>
                <flux:select.option>50</flux:select.option>
                <flux:select.option>100</flux:select.option>
            </flux:select>
        </div>
        {{ $expenses->links() }}
    </div>
</div>
