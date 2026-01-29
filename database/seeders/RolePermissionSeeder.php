<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $$ecom = Role::create(['name' => 'ecom']);
        $admin = Role::create(['name' => 'admin']);

        $ecom->givePermissionTo('create records');

        $admin->givePermissionTo([
            'create records',
            'edit records',
            'delete records',
        ]);
    }
}
