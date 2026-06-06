<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['module' => 'dashboard',  'action' => 'view',   'display_name' => 'View Dashboard'],

            // Products
            ['module' => 'products',   'action' => 'view',   'display_name' => 'View Products'],
            ['module' => 'products',   'action' => 'create', 'display_name' => 'Add Products'],
            ['module' => 'products',   'action' => 'edit',   'display_name' => 'Edit Products'],
            ['module' => 'products',   'action' => 'delete', 'display_name' => 'Delete Products'],

            // Suppliers
            ['module' => 'suppliers',  'action' => 'view',   'display_name' => 'View Suppliers'],
            ['module' => 'suppliers',  'action' => 'create', 'display_name' => 'Add Suppliers'],
            ['module' => 'suppliers',  'action' => 'edit',   'display_name' => 'Edit Suppliers'],
            ['module' => 'suppliers',  'action' => 'delete', 'display_name' => 'Delete Suppliers'],

            // Customers
            ['module' => 'customers',  'action' => 'view',   'display_name' => 'View Customers'],
            ['module' => 'customers',  'action' => 'create', 'display_name' => 'Add Customers'],
            ['module' => 'customers',  'action' => 'edit',   'display_name' => 'Edit Customers'],
            ['module' => 'customers',  'action' => 'delete', 'display_name' => 'Delete Customers'],

            // Sales
            ['module' => 'sales',      'action' => 'view',   'display_name' => 'View Sales'],
            ['module' => 'sales',      'action' => 'create', 'display_name' => 'Create Invoice'],
            ['module' => 'sales',      'action' => 'delete', 'display_name' => 'Delete Invoice'],

            // Recovery
            ['module' => 'recoveries', 'action' => 'view',   'display_name' => 'View Recovery'],
            ['module' => 'recoveries', 'action' => 'create', 'display_name' => 'Record Payment'],
            ['module' => 'recoveries', 'action' => 'delete', 'display_name' => 'Delete Recovery'],

            // Expenses
            ['module' => 'expenses',   'action' => 'view',   'display_name' => 'View Expenses'],
            ['module' => 'expenses',   'action' => 'create', 'display_name' => 'Add Expense'],
            ['module' => 'expenses',   'action' => 'edit',   'display_name' => 'Edit Expense'],
            ['module' => 'expenses',   'action' => 'delete', 'display_name' => 'Delete Expense'],

            // Reports
            ['module' => 'reports',    'action' => 'view',   'display_name' => 'View Reports'],

            // Users
            ['module' => 'users',      'action' => 'view',   'display_name' => 'View Users'],
            ['module' => 'users',      'action' => 'create', 'display_name' => 'Add Users'],
            ['module' => 'users',      'action' => 'edit',   'display_name' => 'Edit Users'],
            ['module' => 'users',      'action' => 'delete', 'display_name' => 'Delete Users'],

            // Roles
            ['module' => 'roles',      'action' => 'view',   'display_name' => 'View Roles'],
            ['module' => 'roles',      'action' => 'create', 'display_name' => 'Add Roles'],
            ['module' => 'roles',      'action' => 'edit',   'display_name' => 'Edit Roles'],
            ['module' => 'roles',      'action' => 'delete', 'display_name' => 'Delete Roles'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['module' => $permission['module'], 'action' => $permission['action']],
                ['display_name' => $permission['display_name']]
            );
        }
    }
}