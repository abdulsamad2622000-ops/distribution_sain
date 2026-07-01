<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    /**
     * Auto-filter every query by the logged-in user's company.
     * - Only applies when a COMPANY user is logged in (company_id present).
     * - Skipped for super admin (company_id = null) and for console/seeders
     *   (no authenticated user), so those can work across all companies.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->hasUser() && auth()->user()->company_id) {
            $builder->where(
                $model->getTable() . '.company_id',
                auth()->user()->company_id
            );
        }
    }
}
