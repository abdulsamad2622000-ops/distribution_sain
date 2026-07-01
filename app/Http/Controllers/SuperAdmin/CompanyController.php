<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::with('plan')
            ->withCount('users')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                                                  ->orWhere('email', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(15);

        return view('superadmin.companies.index', compact('companies'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('superadmin.companies.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'owner_name'           => 'nullable|string|max:255',
            'email'                => 'required|email|unique:companies,email',
            'phone'                => 'nullable|string|max:50',
            'address'              => 'nullable|string|max:255',
            'plan_id'              => 'nullable|exists:subscription_plans,id',
            'status'               => 'required|in:active,inactive,suspended',
            'trial_ends_at'        => 'nullable|date',
            'subscription_ends_at' => 'nullable|date',
            'admin_password'       => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($data, $request) {
            $company = Company::create([
                'name'                 => $data['name'],
                'slug'                 => Str::slug($data['name']) . '-' . Str::random(4),
                'owner_name'           => $data['owner_name'] ?? null,
                'email'                => $data['email'],
                'phone'                => $data['phone'] ?? null,
                'address'              => $data['address'] ?? null,
                'plan_id'              => $data['plan_id'] ?? null,
                'status'               => $data['status'],
                'trial_ends_at'        => $data['trial_ends_at'] ?? null,
                'subscription_ends_at' => $data['subscription_ends_at'] ?? null,
            ]);

            // create the company's first admin login
            User::create([
                'company_id'     => $company->id,
                'name'           => $data['owner_name'] ?: $data['name'] . ' Admin',
                'email'          => $data['email'],
                'password'       => Hash::make($request->admin_password),
                'role'           => 'admin',
                'is_super_admin' => false,
            ]);
        });

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Company created with its admin login.');
    }

    public function edit(Company $company)
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('superadmin.companies.edit', compact('company', 'plans'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'owner_name'           => 'nullable|string|max:255',
            'email'                => 'required|email|unique:companies,email,' . $company->id,
            'phone'                => 'nullable|string|max:50',
            'address'              => 'nullable|string|max:255',
            'plan_id'              => 'nullable|exists:subscription_plans,id',
            'status'               => 'required|in:active,inactive,suspended',
            'trial_ends_at'        => 'nullable|date',
            'subscription_ends_at' => 'nullable|date',
        ]);

        $company->update($data);

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Company updated.');
    }

    public function toggleStatus(Company $company)
    {
        $company->update([
            'status' => $company->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Company status updated to ' . $company->status . '.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return back()->with('success', 'Company deleted.');
    }
}
