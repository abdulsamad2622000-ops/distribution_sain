<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('code')->unique()->after('id');
            $table->string('name')->after('code');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense'])->after('name');
            $table->string('description')->nullable()->after('type');
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['code', 'name', 'type', 'description', 'is_active']);
        });
    }
};