<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToCompany;

class Customer extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = ['company_id', 'name', 'phone', 'address', 'area', 'balance'];

    public function sales()      { return $this->hasMany(Sale::class); }
    public function recoveries() { return $this->hasMany(Recovery::class); }

    public function getTotalSalesAttribute()
    {
        return $this->sales()->sum('net_amount');
    }

    public function getTotalPaidAttribute()
    {
        return $this->recoveries()->sum('amount');
    }
}
