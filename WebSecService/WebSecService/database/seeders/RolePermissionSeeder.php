<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        Permission::create(['name' => 'view_users']);
        Permission::create(['name' => 'edit_users']);
        Permission::create(['name' => 'change_password']);
        Permission::create(['name' => 'delete_users']);
        Permission::create(['name' => 'view_profile']);
        Permission::create(['name' => 'edit_profile']);

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['view_users', 'edit_users', 'change_password', 'delete_users']);

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo(['view_profile', 'edit_profile']);

        $employeeRole = Role::create(['name' => 'employee']);
        $employeeRole->givePermissionTo(['edit_profile']);
    }
}