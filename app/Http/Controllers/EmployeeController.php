<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('name')->paginate(20);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.form', ['employee' => new Employee()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'  => 'required|string|unique:employees,employee_id',
            'name'         => 'required|string|max:255',
            'designation'  => 'required|string|max:255',
            'department'   => 'required|string|max:255',
            'joining_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')
                         ->with('success', 'Employee added successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load('salaries', 'attendance');
        $recentAttendance = $employee->attendance()
                            ->orderBy('date', 'desc')->take(10)->get();
        $recentSalaries   = $employee->salaries()
                            ->orderBy('year', 'desc')
                            ->orderBy('month', 'desc')->take(6)->get();
        return view('employees.show', compact('employee', 'recentAttendance', 'recentSalaries'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.form', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_id'  => 'required|string|unique:employees,employee_id,' . $employee->id,
            'name'         => 'required|string|max:255',
            'designation'  => 'required|string|max:255',
            'department'   => 'required|string|max:255',
            'joining_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')
                         ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')
                         ->with('success', 'Employee deleted.');
    }
}