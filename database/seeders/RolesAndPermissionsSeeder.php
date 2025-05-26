<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Event permissions
            'view events',
            'create events',
            'edit events',
            'delete events',
            'publish events',
            'feature events',
            
            // Event Type permissions
            'view event types',
            'create event types',
            'edit event types',
            'delete event types',
            
            // Location permissions
            'view locations',
            'create locations',
            'edit locations',
            'delete locations',
            
            // Reservation permissions
            'view reservations',
            'create reservations',
            'edit reservations',
            'delete reservations',
            'cancel reservations',
            'confirm reservations',
            
            // User management permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        // Create roles and assign permissions
        $roles = [
            'super-admin' => $permissions,
            'admin' => [
                'view events', 'create events', 'edit events', 'delete events',
                'publish events', 'feature events',
                'view event types', 'create event types', 'edit event types',
                'view locations', 'create locations', 'edit locations',
                'view reservations', 'edit reservations', 'confirm reservations',
                'view users',
            ],
            'organizer' => [
                'view events', 'create events', 'edit events',
                'view event types',
                'view locations',
                'view reservations', 'edit reservations',
            ],
            'user' => [
                'view events',
                'view event types',
                'view locations',
                'create reservations',
                'view reservations',
            ],
        ];

        foreach ($roles as $role => $rolePermissions) {
            $role = Role::create(['name' => $role, 'guard_name' => 'api']);
            $role->givePermissionTo($rolePermissions);
        }
    }
} 