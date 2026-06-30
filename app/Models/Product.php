<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToCompany;

class Product extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id', 'name', 'sku', 'unit', 'purchase_price',
        'selling_price', 'stock_qty', 'low_stock_alert', 'supplier_id'
    ];

    public function supplier()      { return $this->belongsTo(Supplier::class); }
    public function saleItems()     { return $this->hasMany(SaleItem::class); }

    public function isLowStock()
    {
        return $this->stock_qty <= $this->low_stock_alert;
    }

    public function getProfitMarginAttribute()
    {
        if ($this->purchase_price == 0) return 0;
        return round((($this->selling_price - $this->purchase_price) / $this->purchase_price) * 100, 2);
    }
}
