<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToCompany;

class Expense extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id', 'title', 'category', 'amount',
        'user_id', 'expense_date', 'notes'
    ];

    protected $casts = ['expense_date' => 'date'];

    public function user() { return $this->belongsTo(User::class); }
}
