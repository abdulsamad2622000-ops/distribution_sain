<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'billing_cycle', 'max_users',
        'max_invoices', 'features', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class, 'plan_id');
    }

    public function getFeatureListAttribute()
    {
        return $this->features
            ? array_filter(array_map('trim', explode("\n", $this->features)))
            : [];
    }
}
