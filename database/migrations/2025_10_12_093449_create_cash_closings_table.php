<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_closings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opening_id')->nullable()->constrained('cash_openings')->onDelete('cascade'); // ✅ relasi
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('opening_amount', 15, 2);
            $table->decimal('total_sales', 15, 2);
            $table->decimal('total_cash', 15, 2);
            $table->decimal('total_non_cash', 15, 2);
            $table->decimal('total_expenses', 15, 2);
            $table->decimal('expected_cash', 15, 2);
            $table->decimal('actual_cash', 15, 2);
            $table->decimal('difference', 15, 2);
            $table->unsignedTinyInteger('shift_number')->default(1); // ✅ shift ke-berapa
            $table->dateTime('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_closings');
    }
};
