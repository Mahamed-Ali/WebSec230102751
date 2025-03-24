<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'edit_users']);
        // Add other permissions as needed
    }
}