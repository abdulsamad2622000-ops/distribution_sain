<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'company', 'source',
        'status', 'priority', 'estimated_value',
        'notes', 'assigned_to', 'expected_close_date'
    ];

    protected $casts = [
        'expected_close_date' => 'date',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function followups()
    {
        return $this->hasMany(Followup::class);
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }

    public function pendingFollowups()
    {
        return $this->hasMany(Followup::class)->where('status', 'pending');
    }
}