<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cash_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_session_id')->constrained()->cascadeOnDelete();
            $table->string('name');                    // nama belanja
            $table->decimal('amount', 15, 2);          // nominal belanja
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('cash_expenses');
    }
};
