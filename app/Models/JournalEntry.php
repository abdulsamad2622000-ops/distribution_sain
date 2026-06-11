<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference', 'date', 'description', 'status'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function lines()
    {
        return $this->hasMany(JournalLine::class);
    }

    public function getTotalDebitAttribute()
    {
        return $this->lines->where('type', 'debit')->sum('amount');
    }

    public function getTotalCreditAttribute()
    {
        return $this->lines->where('type', 'credit')->sum('amount');
    }
}