<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToCompany;

class Sale extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id', 'invoice_no', 'customer_id', 'user_id', 'total_amount',
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
    $prefix = CompanySetting::get()->invoice_prefix ?: 'INV';
    $prefix = rtrim($prefix, '-') . '-';

    $last = static::withTrashed()->latest('id')->first();
    $number = 1;
    if ($last && preg_match('/(\d+)$/', $last->invoice_no, $m)) {
        $number = (int) $m[1] + 1;
    }
    return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
}
}
