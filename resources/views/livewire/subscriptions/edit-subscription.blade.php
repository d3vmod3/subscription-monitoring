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

    {{-- Splitter selectable network setup dropdowns --}}
    <div class="mt-4">
        <livewire:network-setup.network-setup-dropdowns
            :module="$module"
            :sector-id="$sector_id"
            :pon-id="$pon_id"
            :napbox-id="$napbox_id"
            :splitter-id="$splitter_id" 
            wire:model.live:splitter_id="splitter_id" />
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