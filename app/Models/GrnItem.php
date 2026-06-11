<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_received_note_id',
        'purchase_order_item_id',
        'product_id',
        'qty_received',
        'unit_price',
        'total_price',
    ];

    public function grn()                { return $this->belongsTo(GoodsReceivedNote::class, 'goods_received_note_id'); }
    public function purchaseOrderItem()  { return $this->belongsTo(PurchaseOrderItem::class); }
    public function product()            { return $this->belongsTo(Product::class); }
}