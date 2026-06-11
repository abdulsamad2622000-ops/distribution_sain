<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoodsReceivedNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'grn_number',
        'purchase_order_id',
        'supplier_id',
        'user_id',
        'received_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'received_date' => 'date',
    ];

    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class); }
    public function supplier()      { return $this->belongsTo(Supplier::class); }
    public function user()          { return $this->belongsTo(User::class); }
    public function items()         { return $this->hasMany(GrnItem::class); }

    public static function generateGrnNumber()
    {
        $last   = static::withTrashed()->latest()->first();
        $number = $last ? ((int) substr($last->grn_number, 4)) + 1 : 1;
        return 'GRN-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}