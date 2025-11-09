<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-700 rounded-lg shadow dark:shadow-lg">
    @if (session()->has('message'))
        <div class="mb-4 text-green-600 font-medium">
            {{ session('message') }}
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-6 text-zinc-900 dark:text-zinc-100">Edit Subscriber</h2>

    <form wire:submit.prevent="update" class="space-y-4">
        {{-- First & Middle Name --}}
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block font-medium text-zinc-900 dark:text-zinc-100">First Name</label>
                <input type="text" wire:model.live="first_name" 
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
                @error('first_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1">
                <label class="block font-medium text-zinc-900 dark:text-zinc-100">Middle Name</label>
                <input type="text" wire:model.live="middle_name" 
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
                @error('middle_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Last Name --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Last Name</label>
            <input type="text" wire:model.live="last_name" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            @error('last_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Email</label>
            <input type="email" wire:model.live="email" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Birthdate & Gender --}}
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block font-medium text-zinc-900 dark:text-zinc-100">Birthdate</label>
                <input type="date" wire:model.live="birthdate" 
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
                @error('birthdate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1">
                <label class="block font-medium text-zinc-900 dark:text-zinc-100">Gender</label>
                <select wire:model.live="gender" 
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                @error('gender') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Contact Number --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Contact Number</label>
            <input type="text" wire:model.live="contact_number" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            @error('contact_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Address line 1--}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Address Line 1</label>
            <textarea wire:model.live="address_line_1" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"></textarea>
            @error('address_line_1') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        {{-- Address line 2--}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Address Line 2</label>
            <textarea wire:model.live="address_line_2" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"></textarea>
            @error('address_line_2') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Location Dropdowns --}}
        <div class="mt-4">
            <livewire:address.location-dropdowns 
                :region-id="$region_id"
                :province-id="$province_id"
                :municipality-id="$municipality_id"
                :barangay-id="$barangay_id"
                wire:model.live:region="region_id" 
                wire:model.live:province="province_id" 
                wire:model.live:municipality="municipality_id" 
                wire:model.live:barangay="barangay_id" 
            />
        </div>

        {{-- Status --}}
        <div class="flex justify-end mt-4">
            <flux:field variant="inline">
                <flux:label>Active</flux:label>
                <flux:switch wire:model.live="is_active" />
                <flux:error name="is_active" />
            </flux:field>
            @error('is_active') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex flex-col md:flex-row justify-between mt-4 gap-4">
            <flux:link class="border flex justify-center rounded-xl p-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 dark:hover:text-white transition-colors duration-150"
                href="{{ route('subscribers') }}" style="text-decoration: none;">
                Back
            </flux:link>

            <button type="submit"
                class="bg-zinc-500 text-white px-4 py-2 cursor-pointer rounded hover:bg-zinc-600 dark:hover:bg-zinc-700 transition-colors">
                Save Changes
            </button>
        </div>
        
    </form>
</div>
