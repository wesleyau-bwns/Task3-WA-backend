<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Step 1: Create all permissions first
        $permissions = [
            'view-dashboard',
            'view-admin-page',
            'view-user-page',
            'view-merchant-page',
        ];

        $this->command->info('Creating permissions...');
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web'] // Explicitly set guard
            );
            $this->command->info("Permission created: {$permission}");
        }

        // Step 2: Assign permissions to existing roles
        $rolePermissions = [
            'admin' => [
                'view-dashboard',
                'view-admin-page',
            ],
            'user' => [
                'view-dashboard',
                'view-user-page',
            ],
            'merchant' => [
                'view-dashboard',
                'view-merchant-page',
            ],
        ];

        $this->command->info('Assigning permissions to roles...');

        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();
            
            if ($role) {
                // Get permission objects
                $permissionObjects = Permission::whereIn('name', $permissionNames)->get();
                
                // Sync permissions
                $role->syncPermissions($permissionObjects);
                
                $this->command->info("✓ Assigned " . count($permissionObjects) . " permissions to role: {$roleName}");
            } else {
                $this->command->warn("✗ Role not found: {$roleName}");
            }
        }

        $this->command->info('Permission seeding completed!');
    }
}