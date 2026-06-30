<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToCompany;

class Recovery extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id', 'sale_id', 'customer_id', 'user_id', 'amount',
        'payment_method', 'reference_no', 'payment_date', 'notes'
    ];

    protected $casts = ['payment_date' => 'date'];

    public function sale()     { return $this->belongsTo(Sale::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function user()     { return $this->belongsTo(User::class); }
}
