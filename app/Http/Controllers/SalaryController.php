<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Employee;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = Salary::with('employee')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->paginate(20);
        return view('salaries.index', compact('salaries'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        return view('salaries.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'  => 'required|exists:employees,id',
            'month'        => 'required|integer|between:1,12',
            'year'         => 'required|integer|min:2000',
            'basic_salary' => 'required|numeric|min:0',
            'allowances'   => 'nullable|numeric|min:0',
            'deductions'   => 'nullable|numeric|min:0',
        ]);

        $net = $request->basic_salary
             + ($request->allowances ?? 0)
             - ($request->deductions ?? 0);

        Salary::create([
            'employee_id'  => $request->employee_id,
            'month'        => $request->month,
            'year'         => $request->year,
            'basic_salary' => $request->basic_salary,
            'allowances'   => $request->allowances ?? 0,
            'deductions'   => $request->deductions ?? 0,
            'net_salary'   => $net,
            'status'       => 'pending',
            'notes'        => $request->notes,
        ]);

        return redirect()->route('salaries.index')
                         ->with('success', 'Salary record created.');
    }

    public function show(Salary $salary)
    {
        $salary->load('employee');
        return view('salaries.show', compact('salary'));
    }

    public function markPaid(Salary $salary)
    {
        $salary->update([
            'status'    => 'paid',
            'paid_date' => now()->toDateString(),
        ]);
        return back()->with('success', 'Salary marked as paid.');
    }

    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salaries.index')
                         ->with('success', 'Salary record deleted.');
    }
}