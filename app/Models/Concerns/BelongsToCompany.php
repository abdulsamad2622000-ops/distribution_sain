<?php

namespace App\Models\Concerns;

use App\Models\Company;
use App\Models\Scopes\CompanyScope;

trait BelongsToCompany
{
    /**
     * Booted automatically by Eloquent for any model using this trait.
     */
    protected static function bootBelongsToCompany(): void
    {
        // 1. Read isolation: every query is filtered by the current company.
        static::addGlobalScope(new CompanyScope);

        // 2. Write isolation: stamp the company_id on every new record
        //    when a company user is creating it (and it's not already set).
        static::creating(function ($model) {
            if (empty($model->company_id) && auth()->hasUser() && auth()->user()->company_id) {
                $model->company_id = auth()->user()->company_id;
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
