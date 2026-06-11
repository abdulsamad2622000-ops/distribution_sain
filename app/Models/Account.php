<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'type', 'description', 'is_active'
    ];

    public function journalLines()
    {
        return $this->hasMany(JournalLine::class);
    }
}