<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'name', 'email', 'phone',
        'designation', 'department', 'joining_date',
        'basic_salary', 'status', 'address', 'cnic'
    ];

    protected $casts = [
        'joining_date' => 'date',
    ];

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}