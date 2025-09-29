<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User permissions
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',

            // Car permissions
            'car.view',
            'car.create',
            'car.edit',
            'car.delete',

            // Brand permissions
            'brand.view',
            'brand.create',
            'brand.edit',
            'brand.delete',

            // Role & Permission management
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',
            'permission.view',
            'permission.assign',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo([
            'user.view',
            'user.create',
            'user.edit',
            'car.view',
            'car.create',
            'car.edit',
            'car.delete',
            'brand.view',
            'brand.create',
            'brand.edit',
            'brand.delete',
        ]);

        $managerRole = Role::create(['name' => 'Manager']);
        $managerRole->givePermissionTo([
            'car.view',
            'car.create',
            'car.edit',
            'brand.view',
            'brand.create',
            'brand.edit',
            'user.view',
        ]);

        $userRole = Role::create(['name' => 'User']);
        $userRole->givePermissionTo([
            'car.view',
            'brand.view',
        ]);

        // Create users and assign roles
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $superAdmin->assignRole($superAdminRole);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $admin->assignRole($adminRole);

        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $manager->assignRole($managerRole);

        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $regularUser->assignRole($userRole);
    }
}
