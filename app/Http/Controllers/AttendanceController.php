<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date      = $request->date ?? today()->toDateString();
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        $records   = Attendance::with('employee')
                     ->where('date', $date)->get()
                     ->keyBy('employee_id');

        return view('attendance.index', compact('employees', 'records', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'         => 'required|date',
            'attendance'   => 'required|array',
        ]);

        foreach ($request->attendance as $employeeId => $status) {
            Attendance::updateOrCreate(
                ['employee_id' => $employeeId, 'date' => $request->date],
                ['status' => $status]
            );
        }

        return back()->with('success', 'Attendance saved successfully.');
    }

    public function report(Request $request)
    {
        $month     = $request->month ?? date('m');
        $year      = $request->year  ?? date('Y');
        $employees = Employee::where('status', 'active')
                    ->with(['attendance' => function ($q) use ($month, $year) {
                        $q->whereMonth('date', $month)->whereYear('date', $year);
                    }])->orderBy('name')->get();

        return view('attendance.report', compact('employees', 'month', 'year'));
    }
}