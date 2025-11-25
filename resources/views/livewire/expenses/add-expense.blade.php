<div class="space-y-6  p-4 w-full mx-auto">
    
    <h2 class="text-lg font-semibold">Add New Expense</h2>
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

    {{-- Buttons --}}
    <div class="flex justify-end space-x-2 mt-4">
        <flux:button wire:click="save" variant="primary">Save</flux:button>
    </div>
</div>
