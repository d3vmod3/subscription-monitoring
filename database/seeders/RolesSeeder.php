<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cached roles/permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole  = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Assign all permissions to admin
        $adminRole->syncPermissions(Permission::all());

        // Assign permissions to user
        $userPermissions = Permission::where('name', 'like', 'view %')->get()
            ->merge(Permission::where('name', 'add Payment')->get());

        $userRole->syncPermissions($userPermissions);
    }
}
