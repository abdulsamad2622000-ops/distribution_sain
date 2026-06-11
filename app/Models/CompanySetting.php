<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
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

    // Hamesha ek hi row rehti hai id=1
    public static function get(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'company_name'    => 'My Company',
            'currency_symbol' => 'PKR',
            'currency_code'   => 'PKR',
            'invoice_prefix'  => 'INV',
        ]);
    }
}