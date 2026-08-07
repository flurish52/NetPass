<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage plans',
            'manage vouchers',
            'view sessions',
            'view reports',
            'manage staff',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        Role::firstOrCreate(['name' => 'admin']); // no business-scoped permissions — checked via hasRole() instead

        $owner = Role::firstOrCreate(['name' => 'owner']);
        $owner->givePermissionTo($permissions);

        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->givePermissionTo(['manage vouchers', 'view sessions']);
    }
}
