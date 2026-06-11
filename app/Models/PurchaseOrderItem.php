<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'qty',
        'unit_price',
        'total_price',
        'received_qty',
    ];

    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class); }
    public function product()       { return $this->belongsTo(Product::class); }
    public function grnItems()      { return $this->hasMany(GrnItem::class); }

    public function getRemainingQtyAttribute()
    {
        return $this->qty - $this->received_qty;
    }
}