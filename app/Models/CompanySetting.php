<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToCompany;

class CompanySetting extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'company_name',
        'company_tagline',
        'company_email',
        'company_phone',
        'company_address',
        'company_city',
        'company_country',
        'tax_number',
        'registration_number',
        'currency_symbol',
        'currency_code',
        'invoice_prefix',
        'financial_year_start',
        'tax_percentage',
        'logo_path',
    ];

    /**
     * Returns the settings row for the CURRENTLY logged-in company.
     * Har company ka apna ek row — pehli baar access par auto-ban jata hai.
     */
    public static function get(): self
    {
        $companyId = auth()->user()->company_id;

        return static::firstOrCreate(
            ['company_id' => $companyId],
            [
                'company_name'    => auth()->user()->company->name ?? 'My Company',
                'company_email'   => auth()->user()->company->email ?? null,
                'currency_symbol' => 'PKR',
                'currency_code'   => 'PKR',
                'invoice_prefix'  => 'INV',
            ]
        );
    }
}
