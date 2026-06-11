<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_tagline')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_country')->default('Pakistan');
            $table->string('tax_number')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('currency_symbol')->default('PKR');
            $table->string('currency_code')->default('PKR');
            $table->string('invoice_prefix')->default('INV');
            $table->string('financial_year_start')->default('01');
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};