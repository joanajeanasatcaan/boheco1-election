<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $ecom = Role::create(['name' => 'ecom']);
        $admin = Role::create(['name' => 'admin']);
    }
}
