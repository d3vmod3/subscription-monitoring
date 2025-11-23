<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;

class UserAccessControl extends Component
{
    public $roles = [];
    public $permissions = [];
    public $groupedPermissions = [];

    public $selectedRole = null;
    public $selectedPermissions = [];

    public $mode = 'view'; // view | edit

    public function mount()
    {
        $this->roles = Role::all();
        $this->permissions = Permission::all();
        $this->groupPermissions();
    }

    public function selectRole($roleId)
    {
        $this->selectedRole = Role::find($roleId);
        $this->mode = 'edit';

        // Pre-select current permissions
        if($this->selectedRole)
        {
            $this->selectedPermissions = $this->selectedRole
            ->permissions
            ->pluck('name')
            ->toArray();
        }
        else
        {
            $this->selectedPermissions = [];
            $this->mode = 'view';
        }
    }

    private function groupPermissions()
    {
        $this->groupedPermissions = [];

        // Step 1: Sort permissions by name ascending
        $sortedPermissions = $this->permissions->sortBy('name');

        // Step 2: Group them by module
        foreach ($sortedPermissions as $perm) {
            $parts = explode(' ', $perm->name, 2);
            $action = $parts[0] ?? '';
            $module = $parts[1] ?? '';

            if (!isset($this->groupedPermissions[$module])) {
                $this->groupedPermissions[$module] = [];
            }

            $this->groupedPermissions[$module][] = [
                'name'   => $perm->name,
                'action' => $action,
            ];
        }

        // Step 3: Sort modules alphabetically
        ksort($this->groupedPermissions);
    }

    public function togglePermission($permissionName, $checked)
    {
        if (!$this->selectedRole) return;

        $role = Role::find($this->selectedRole->id);

        if ($checked) {
            $role->givePermissionTo($permissionName);
        } else {
            $role->revokePermissionTo($permissionName);
        }

        // refresh selected permissions
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();

        $this->dispatch('show-toast', [
           'message' => $checked
                ? "Permission " . ucwords($permissionName) . " granted to " . ucwords($role->name)
                : "Permission " . ucwords($permissionName) . " removed from " . ucwords($role->name),
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        if(Auth::user()->hasRole('admin'))
        {
            return view('livewire.admin.user-access-control');
        }
        else
        {
            abort(403, 'You are not allowed to view this page');
        }
        
    }
}
