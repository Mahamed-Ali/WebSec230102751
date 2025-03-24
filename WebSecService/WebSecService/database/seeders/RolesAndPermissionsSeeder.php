<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view_users',
            'edit_users',
            'change_password',
            'delete_users',
            'view_profile',
            'edit_profile'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo(['view_profile', 'edit_profile']);

        $employeeRole = Role::create(['name' => 'employee']);
        $employeeRole->givePermissionTo(['edit_profile']);

        // Assign admin role to specific user
        $admin = User::where('email', 'marwan@gmail.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }
    }
}
