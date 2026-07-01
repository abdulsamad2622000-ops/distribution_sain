<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 12, 2)->default(0);
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->integer('max_users')->nullable();      // null = unlimited
            $table->integer('max_invoices')->nullable();   // null = unlimited
            $table->text('features')->nullable();          // newline separated
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
