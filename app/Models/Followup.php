<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Followup extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id', 'user_id', 'type',
        'followup_date', 'notes', 'status'
    ];

    protected $casts = [
        'followup_date' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}