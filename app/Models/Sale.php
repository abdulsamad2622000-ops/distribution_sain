<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_no', 'customer_id', 'user_id', 'total_amount',
        'discount', 'net_amount', 'paid_amount', 'due_amount',
        'payment_type', 'status', 'sale_date', 'notes'
    ];

    protected $casts = ['sale_date' => 'date'];

    public function customer()   { return $this->belongsTo(Customer::class); }
    public function user()       { return $this->belongsTo(User::class); }
    public function items()      { return $this->hasMany(SaleItem::class); }
    public function recoveries() { return $this->hasMany(Recovery::class); }

    public function getProfitAttribute()
    {
        return $this->items->sum(fn($item) =>
            ($item->unit_price - $item->purchase_price) * $item->qty
        );
    }

    public static function generateInvoiceNo()
    {
        $last = static::withTrashed()->latest()->first();
        $number = $last ? ((int) substr($last->invoice_no, 4)) + 1 : 1;
        return 'INV-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}