<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToCompany;

class Role extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'name', 'display_name', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission($module, $action)
    {
        return $this->permissions()
            ->where('module', $module)
            ->where('action', $action)
            ->exists();
    }
}
