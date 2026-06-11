<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'user_id',
        'status',
        'order_date',
        'expected_date',
        'total_amount',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'order_date'    => 'date',
        'expected_date' => 'date',
        'approved_at'   => 'datetime',
    ];

    public function supplier()    { return $this->belongsTo(Supplier::class); }
    public function user()        { return $this->belongsTo(User::class); }
    public function approvedBy()  { return $this->belongsTo(User::class, 'approved_by'); }
    public function items()       { return $this->hasMany(PurchaseOrderItem::class); }
    public function grns()        { return $this->hasMany(GoodsReceivedNote::class); }

    public static function generatePoNumber()
    {
        $last   = static::withTrashed()->latest()->first();
        $number = $last ? ((int) substr($last->po_number, 3)) + 1 : 1;
        return 'PO-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function getTotalReceivedAttribute()
    {
        return $this->items->sum('received_qty');
    }
}