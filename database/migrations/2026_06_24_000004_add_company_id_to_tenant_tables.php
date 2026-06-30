<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // --- Simple tables: just add company_id ---
        foreach (['suppliers', 'customers', 'sale_items', 'recoveries', 'expenses'] as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()->after('id')
                      ->constrained('companies')->nullOnDelete();
            });
        }

        // --- products: company_id + make SKU unique PER company ---
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')
                  ->constrained('companies')->nullOnDelete();
            $table->dropUnique(['sku']);            // drop global unique
            $table->unique(['company_id', 'sku']);  // unique within a company
        });

        // --- sales: company_id + invoice_no unique PER company ---
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')
                  ->constrained('companies')->nullOnDelete();
            $table->dropUnique(['invoice_no']);
            $table->unique(['company_id', 'invoice_no']);
        });

        // --- roles: company_id + role name unique PER company ---
        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')
                  ->constrained('companies')->nullOnDelete();
            $table->dropUnique(['name']);
            $table->unique(['company_id', 'name']);
        });
    }

    public function down(): void
    {
        foreach (['suppliers', 'customers', 'sale_items', 'recoveries', 'expenses'] as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            });
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'sku']);
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->unique('sku');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'invoice_no']);
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->unique('invoice_no');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'name']);
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->unique('name');
        });
    }
};
