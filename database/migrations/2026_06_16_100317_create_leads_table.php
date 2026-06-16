<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('source')->nullable(); // call, website, referral, walk-in
            $table->enum('status', ['new', 'contacted', 'negotiation', 'won', 'lost'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->decimal('estimated_value', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->date('expected_close_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};