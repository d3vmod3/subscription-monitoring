<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-700 rounded-lg shadow dark:shadow-lg">

    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="mb-4 text-green-600 font-medium text-center sm:text-left">
            {{ session('message') }}
        </div>
    @endif

    <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-zinc-900 dark:text-zinc-100 text-center sm:text-left">
        Add Subscriber
    </h2>

    <form wire:submit.prevent="save" class="space-y-4">

        {{-- First & Middle Name --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-zinc-900 dark:text-zinc-100">First Name</label>
                <input type="text" wire:model="first_name" 
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('first_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium text-zinc-900 dark:text-zinc-100">Middle Name</label>
                <input type="text" wire:model="middle_name" 
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('middle_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Last Name --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Last Name</label>
            <input type="text" wire:model="last_name" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('last_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Email</label>
            <input type="email" wire:model="email" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Birthdate & Gender --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-zinc-900 dark:text-zinc-100">Birthdate</label>
                <input type="date" wire:model="birthdate" 
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('birthdate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium text-zinc-900 dark:text-zinc-100">Gender</label>
                <select wire:model="gender" 
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
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
            <input type="text" wire:model="contact_number" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('contact_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Address lines --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Address Line 1</label>
            <textarea wire:model="address_line_1" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            @error('address_line_1') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Address Line 2</label>
            <textarea wire:model="address_line_2" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            @error('address_line_2') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Location Dropdowns --}}
        <div class="mt-4">
            <livewire:address.location-dropdowns 
                wire:model.live:region="region_id" 
                wire:model.live:province="province_id" 
                wire:model.live:municipality="municipality_id" 
                wire:model.live:barangay="barangay_id" />
        </div>

        {{-- Status --}}
        <div class="flex items-center mt-4">
            <flux:field variant="inline" class="flex items-center space-x-2">
                <flux:label>Active</flux:label>
                <flux:switch wire:model="is_active" />
                <flux:error name="is_active" />
            </flux:field>
            @error('is_active') <span class="text-red-600 text-sm ml-2">{{ $message }}</span> @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex flex-col md:flex-row justify-between mt-6 gap-4">
            <flux:link class="border flex justify-center rounded-xl p-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 dark:hover:text-white transition-colors duration-150 w-full md:w-auto text-center"
                href="{{ route('subscribers') }}" style="text-decoration: none;">
                Back
            </flux:link>

            <flux:button type="submit" variant="primary">
                Save
            </flux:button>
        </div>

    </form>
</div>
