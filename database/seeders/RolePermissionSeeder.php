<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define all permissions
        $permissions = [
            'vouchers.manage',
            'expenses.manage',
            'expenses.approve',
            'collections.view',
            'reports.view',
            'sales.create',
            'sales.view',
            'sales.discount.request',
            'units.view',
            'units.manage',
            'projects.manage',
            'projects.view',
            'units.rate.manage',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // 2. Define Roles and Assign Permissions
        $owner = Role::firstOrCreate(['name' => 'Owner', 'guard_name' => 'web']);
        $owner->syncPermissions(Permission::all());

        $accountant = Role::firstOrCreate(['name' => 'Accountant', 'guard_name' => 'web']);
        $accountant->syncPermissions([
            'vouchers.manage',
            'expenses.manage',
            'expenses.approve',
            'collections.view',
            'reports.view',
            'units.view',
            'units.rate.manage',
        ]);

        $sales = Role::firstOrCreate(['name' => 'Sales', 'guard_name' => 'web']);
        $sales->syncPermissions([
            'sales.create',
            'sales.view',
            'sales.discount.request',
            'units.view',
            'collections.view',
        ]);

        $site = Role::firstOrCreate(['name' => 'Site', 'guard_name' => 'web']);
        $site->syncPermissions([
            'units.manage',
            'units.view',
        ]);
    }
}
