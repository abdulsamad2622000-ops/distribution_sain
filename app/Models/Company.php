<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'owner_name', 'email', 'phone', 'address',
        'logo', 'plan_id', 'status', 'trial_ends_at', 'subscription_ends_at',
    ];

    protected $casts = [
        'trial_ends_at'        => 'date',
        'subscription_ends_at' => 'date',
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isExpired()
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isPast();
    }
}
