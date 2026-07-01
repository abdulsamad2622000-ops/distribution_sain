<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::withCount('companies')->latest()->get();
        return view('superadmin.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePlan($request);
        SubscriptionPlan::create($data);

        return back()->with('success', 'Plan created.');
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $data = $this->validatePlan($request);
        $plan->update($data);

        return back()->with('success', 'Plan updated.');
    }

    public function toggleStatus(SubscriptionPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        return back()->with('success', 'Plan status updated.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        if ($plan->companies()->exists()) {
            return back()->with('error', 'Cannot delete — companies are using this plan.');
        }

        $plan->delete();
        return back()->with('success', 'Plan deleted.');
    }

    private function validatePlan(Request $request): array
    {
        return $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'max_users'     => 'nullable|integer|min:1',
            'max_invoices'  => 'nullable|integer|min:1',
            'features'      => 'nullable|string',
            'is_active'     => 'nullable|boolean',
        ]);
    }
}
