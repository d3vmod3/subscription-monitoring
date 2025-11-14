<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get all Eloquent models
        $modelsPath = app_path('Models');
        $models = [];

        foreach (File::allFiles($modelsPath) as $file) {
            $namespace = 'App\\Models\\';
            $class = $namespace . str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());
            if (class_exists($class) && is_subclass_of($class, Model::class)) {
                $models[] = $class;
            }
        }

        // Create permissions
        foreach ($models as $model) {
            $modelName = class_basename($model);

            // Admin permissions: view, add, edit, delete
            foreach (['view', 'add', 'edit', 'delete'] as $action) {
                Permission::firstOrCreate(['name' => strtolower($action . ' ' . $modelName), 'guard_name' => 'web']);
            }

            // Special: user can add only Payment
            if ($modelName === 'Payment') {
                Permission::firstOrCreate(['name' => 'add Payment', 'guard_name' => 'web']);
            }
        }
    }
}
