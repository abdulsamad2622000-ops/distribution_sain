<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super admin login (platform owner)
        User::updateOrCreate(
            ['email' => 'superadmin@nsh.com'],
            [
                'name'           => 'Super Admin',
                'password'       => Hash::make('password'),
                'role'           => 'admin',
                'is_super_admin' => true,
                'company_id'     => null,
            ]
        );

        // Sample subscription plans
        $plans = [
            [
                'name' => 'Starter', 'price' => 2000, 'billing_cycle' => 'monthly',
                'max_users' => 3, 'max_invoices' => 200,
                'features' => "Sales & Invoicing\nCustomers\nBasic Reports",
            ],
            [
                'name' => 'Business', 'price' => 5000, 'billing_cycle' => 'monthly',
                'max_users' => 10, 'max_invoices' => 1000,
                'features' => "Everything in Starter\nInventory & Warehouse\nRecovery & Expenses\nPDF Reports",
            ],
            [
                'name' => 'Enterprise', 'price' => 12000, 'billing_cycle' => 'monthly',
                'max_users' => null, 'max_invoices' => null,
                'features' => "Everything in Business\nAccounting & Ledger\nHR & Payroll\nUnlimited users",
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
