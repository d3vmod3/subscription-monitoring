<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class PermissionsSeeder extends Seeder
{
    private function cleanModelName($name)
    {
        // Convert CamelCase → spaced words (e.g., PassiveOpticalNetwork → "passive optical network")
        $spaced = preg_replace('/(?<!^)([A-Z])/', ' $1', $name);

        return str($spaced)->lower()->plural();   // pluralized
    }

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modelsPath = app_path('Models');
        $models = [];

        // Models to exclude
        $excluded = ["Barangay", "Municipality", "City", "Province", "Region"];

        foreach (File::allFiles($modelsPath) as $file) {

            $class = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());

            if (
                class_exists($class) &&
                is_subclass_of($class, Model::class) &&
                !in_array(class_basename($class), $excluded)  // FIXED
            ) {
                $models[] = $class;
            }
        }

        foreach ($models as $model) {

            $modelName = class_basename($model);
            $cleanName = $this->cleanModelName($modelName);   // pluralized name

            foreach (['view', 'add', 'edit', 'delete'] as $action) {
                Permission::firstOrCreate([
                    'name' => "$action $cleanName",
                    'guard_name' => 'web',
                ]);
            }

            // Optional special case (still valid)
            if ($modelName === 'Payment') {
                Permission::firstOrCreate([
                    'name' => 'add payments',
                    'guard_name' => 'web'
                ]);
            }
        }
    }
}
