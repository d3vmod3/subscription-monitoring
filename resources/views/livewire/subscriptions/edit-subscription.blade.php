<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-700 rounded-lg shadow space-y-6 dark:shadow-lg">
    <h2 class="text-lg font-semibold mb-4">Edit Subscription</h2>

    {{-- Subscriber (Searchable Dropdown) --}}
    <div class="relative">
        <label class="block text-sm font-medium mb-1">Subscriber</label>
        <input 
            type="text" 
            wire:model.live="subscriber_search" 
            class="w-full border rounded px-3 py-2"
            placeholder="Search subscriber by name or email..."
        >
        @if(!empty($subscriber_results))
            <ul class="absolute z-10 bg-gray-100 dark:bg-gray-600 border rounded mt-1 w-full shadow-md max-h-48 overflow-y-auto">
                @foreach($subscriber_results as $subscriber)
                    <li 
                        wire:click="selectSubscriber({{ $subscriber->id }}, '{{ $subscriber->first_name }} {{ $subscriber->last_name }}')" 
                        class="px-3 py-2 hover:bg-gray-100 hover:text-gray-600 cursor-pointer"
                    >
                        {{ $subscriber->first_name }} {{ $subscriber->last_name }}
                        <span class="text-sm">({{ $subscriber->email }})</span>
                    </li>
                @endforeach
            </ul>
        @endif
        @error('subscriber_id') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Plan --}}
    <div>
        <label class="block text-sm font-medium mb-1">Plan</label>
        <select wire:model.defer="plan_id" class="w-full border rounded px-3 py-2">
            <option value="">-- Select Plan --</option>
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}">
                    {{ $plan->plan_name }} - ₱{{ number_format($plan->price, 2) }}
                </option>
            @endforeach
        </select>
        @error('plan_id') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- PON --}}
    <div>
        <label class="block text-sm font-medium mb-1">PON</label>
        <select wire:model.defer="pon_id" class="w-full border rounded px-3 py-2">
            <option value="">-- Select PON (optional) --</option>
            @foreach($pons as $pon)
                <option value="{{ $pon->id }}">{{ $pon->name }}</option>
            @endforeach
        </select>
        @error('pon_id') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Mikrotik Name --}}
    <div>
        <label class="block text-sm font-medium mb-1">Mikrotik Name</label>
        <input 
            type="text" 
            wire:model.defer="mikrotik_name" 
            class="w-full border rounded px-3 py-2"
        >
        @error('mikrotik_name') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Start Date --}}
    <div>
        <label class="block text-sm font-medium mb-1">Start Date</label>
        <input 
            type="date" 
            wire:model.defer="start_date" 
            class="w-full border rounded px-3 py-2"
        >
        @error('start_date') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Due Day --}}
    <div>
        <label class="block text-sm font-medium mb-1">Due Day</label>
        <input 
            type="number" 
            wire:model.defer="due_day" 
            class="w-full border rounded px-3 py-2"
            placeholder="Enter due day of the month"
            min="1"
            max="28"
        >
        @error('due_day') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Status --}}
    <div>
        <label class="block text-sm font-medium mb-1">Status</label>
        <select wire:model.defer="status" class="w-full border rounded px-3 py-2">
            <option value="inactive">Inactive</option>
            <option value="active">Active</option>
            <option value="disconnected">Disconnected</option>
        </select>
        @error('status') 
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
        @enderror
    </div>

    {{-- Buttons --}}
    <div class="flex justify-end space-x-2 mt-4">
        <flux:button wire:click="save" wire:loading.attr="disabled" variant="primary">
            <span wire:loading.remove>Update</span>
            <span wire:loading>Updating...</span>
        </flux:button>
        <flux:link href="{{ route('subscriptions') }}" variant="secondary"
            class="border flex justify-center rounded-xl p-2 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-150"
            style="text-decoration: none;">Subscriptions List</flux:link>
    </div>
</div>