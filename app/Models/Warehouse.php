<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'incharge_name',
        'incharge_phone',
        'is_active',
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}