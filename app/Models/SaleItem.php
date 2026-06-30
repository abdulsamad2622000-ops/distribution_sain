<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToCompany;

class SaleItem extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id', 'sale_id', 'product_id', 'qty',
        'unit_price', 'purchase_price', 'total_price'
    ];

    public function sale()    { return $this->belongsTo(Sale::class); }
    public function product() { return $this->belongsTo(Product::class); }

    public function getProfitAttribute()
    {
        return ($this->unit_price - $this->purchase_price) * $this->qty;
    }
}
