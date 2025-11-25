<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-700 rounded-lg shadow space-y-6 dark:shadow-lg">
    @if($expense)
    <h2 class="text-lg font-semibold">Edit Expense</h2>
    {{-- Title --}}
    <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input 
            type="text" 
            wire:model.defer="title" 
            class="w-full border rounded px-3 py-2"
            placeholder="Enter Expense Title"
        >
        @error('title') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea 
            wire:model.defer="description" 
            class="w-full border rounded px-3 py-2" 
            rows="3"
            placeholder="Optional description..."
        ></textarea>
        @error('description') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
        <div>
            <label class="block text-sm font-medium mb-1">Date Time Issued</label>
            <input type="datetime-local"  wire:model.defer="date_time_issued" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('date_time_issued') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Payment Method --}}
    <div>
        <label class="block text-sm font-medium mb-1">Payment Method</label>
        <select wire:model.defer="payment_method_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="">-- Select Payment Method --</option>
            @foreach ($payment_methods as $method)
                <option value="{{ $method->id }}">{{ $method->name }}</option>
            @endforeach
        </select>
        @error('payment_method_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Reference Number --}}
    <div>
        <label class="block text-sm font-medium">Reference Number</label>
        <input type="text" wire:model.defer="reference_number" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Optional reference number">
        @error('reference_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Amount --}}
    <div>
        <label class="block text-sm font-medium mb-1">Total Amount</label>
        <input type="number" step="0.01" wire:model.defer="amount" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Enter total expense amount">
        @error('amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    
    </div>

    {{-- ⚙️ Status --}}
    <div>
        <label class="block font-medium text-zinc-900 dark:text-zinc-100">Status</label>
        <select wire:model.defer="status"
            class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 
                   border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="Approved">Approved</option>
            <option value="Disapproved">Disapproved</option>
            <option value="Pending">Pending</option>
        </select>
        @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4">
        <flux:button wire:click="save" wire:loading.attr="disabled" variant="primary">
            <span wire:loading.remove>Update</span>
            <span wire:loading>Updating...</span>
        </flux:button>

        <flux:link href="{{ route('expenses') }}" variant="secondary"
            class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150 w-full sm:w-auto text-center"
            style="text-decoration: none;">
            Back to Expenses
        </flux:link>
        @can('delete expenses')
        <div>
            <flux:modal.trigger name="delete-profile">
            <flux:button variant="danger" class="border flex justify-center rounded-xl p-2 w-full sm:w-auto text-center">Delete</flux:button>
            </flux:modal.trigger>

            <flux:modal name="delete-profile" class="min-w-[22rem]">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Delete expense?</flux:heading>
                            <flux:text class="mt-2">
                                Are you sure you want to delete this expense? <br>
                                {{ $title }}
                            </flux:text>
                    </div>
                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:button type="submit" variant="danger" wire:click="delete">Yes</flux:button>

                        <flux:modal.close>
                            <flux:button variant="ghost">Cancel</flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            </flux:modal>
        </div>
        @endcan
    </div>
    @else
    <div class="flex flex-col items-center justify-center min-h-screen space-y-4 text-center">
         <h2 class="text-lg font-semibold text-center">This expense have been deleted</h2>
        <flux:link href="{{ route('expenses') }}" variant="secondary"
            class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150 text-center"
            style="text-decoration: none;">
            Back to Expenses
        </flux:link>
    </div>
    @endif
</div>
