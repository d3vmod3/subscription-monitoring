<div class="mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">User Access Control</h1>

    {{-- Role Selector --}}
    <div class="rounded flex items-center space-x-2 mb-6">
        <h2 class="text-xl font-semibold">Role</h2>
        <select
            wire:change="selectRole($event.target.value)"
            class="border rounded px-3 py-2 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 border-gray-300 dark:border-gray-600"
        >
            <option value="">-- Select Role --</option>
            @foreach ($roles as $role)
                <option
                    value="{{ $role->id }}"
                    @if ($selectedRole && $selectedRole->id == $role->id) selected @endif
                >
                    {{ ucwords($role->name) }}
                </option>
            @endforeach
        </select>
    </div>


    {{-- Permissions Editor --}}
    <div class="grid grid-cols-4 gap-4">
        @foreach ($groupedPermissions as $module => $permissions)
            <div class="border rounded p-4">

                {{-- Module Header --}}
                <h3 class="text-lg font-semibold mb-2 capitalize bg-dark">
                    {{ $module }}
                </h3>

                {{-- Actions --}}
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($permissions as $permission)
                        <flux:field variant="inline">
                            <flux:checkbox
                                wire:model="selectedPermissions"
                                wire:change="togglePermission('{{ $permission['name'] }}', $event.target.checked)" value="{{ $permission['name'] }}"
                                :disabled="!$selectedRole"
                            />
                            <flux:label class="capitalize">{{ $permission['action'] }}</flux:label>
                            <flux:error name="selectedPermissions" />
                        </flux:field>
                    @endforeach
                </div>

            </div>
        @endforeach
    </div>
    
</div>
