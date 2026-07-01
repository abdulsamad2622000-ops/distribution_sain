<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'role_id',
        'company_id', 'is_super_admin',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_super_admin'    => 'boolean',
        ];
    }

    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isSuperAdmin()
    {
        return $this->is_super_admin === true;
    }

    public function hasPermission($module, $action)
    {
        if ($this->role === 'admin') return true;
        if (!$this->roleModel) return false;
        return $this->roleModel->hasPermission($module, $action);
    }

    public function sales()      { return $this->hasMany(Sale::class); }
    public function recoveries() { return $this->hasMany(Recovery::class); }
    public function expenses()   { return $this->hasMany(Expense::class); }
}
