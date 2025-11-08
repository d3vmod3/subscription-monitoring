
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
                <input type="text" wire:model="first_name" 
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
                @error('first_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1">
                <label class="block font-medium text-zinc-900 dark:text-zinc-100">Middle Name</label>
                <input type="text" wire:model="middle_name" 
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
                @error('middle_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Last Name --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Last Name</label>
            <input type="text" wire:model="last_name" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            @error('last_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Email</label>
            <input type="email" wire:model="email" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Birthdate & Gender --}}
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block font-medium text-zinc-900 dark:text-zinc-100">Birthdate {{$birthdate}}</label>
                <input type="date" wire:model="birthdate" 
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
                @error('birthdate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1">
                <label class="block font-medium text-zinc-900 dark:text-zinc-100">Gender</label>
                <select wire:model="gender" 
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
            <input type="text" wire:model="contact_number" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
            @error('contact_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Address --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Address</label>
            <textarea wire:model="address" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"></textarea>
            @error('address') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Status --}}
        <div>
            <label class="block font-medium text-zinc-900 dark:text-zinc-100">Status</label>
            <select wire:model="status" 
                class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
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
    <livewire:toast />
</div>
