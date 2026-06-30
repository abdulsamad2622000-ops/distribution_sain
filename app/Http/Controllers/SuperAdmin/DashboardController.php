<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\SubscriptionPlan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCompanies  = Company::count();
        $activeCompanies = Company::where('status', 'active')->count();
        $inactiveCompanies = Company::where('status', '!=', 'active')->count();
        $totalPlans      = SubscriptionPlan::count();
        $totalUsers      = User::where('is_super_admin', false)->count();

        // estimated monthly revenue from active companies' plans
        $monthlyRevenue = Company::where('status', 'active')
            ->with('plan')->get()
            ->sum(fn($c) => $c->plan
                ? ($c->plan->billing_cycle === 'yearly'
                    ? $c->plan->price / 12
                    : $c->plan->price)
                : 0);

        $recentCompanies = Company::with('plan')->latest()->take(6)->get();

        return view('superadmin.dashboard', compact(
            'totalCompanies', 'activeCompanies', 'inactiveCompanies',
            'totalPlans', 'totalUsers', 'monthlyRevenue', 'recentCompanies'
        ));
    }
}
