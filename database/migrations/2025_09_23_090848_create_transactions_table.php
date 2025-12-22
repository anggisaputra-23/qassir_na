<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();               // kode unik transaksi
            $table->decimal('total', 15, 2);                // total nominal
            $table->string('payment_method')->default('cash'); // metode pembayaran: cash, gopay, qris, dll
            $table->enum('status', ['success', 'pending', 'cancel', 'paid'])->default('pending'); // status transaksi
            $table->text('note')->nullable();               // catatan kasir
            $table->string('customer_name')->nullable();    // nama customer
            $table->string('order_type')->default('dine_in'); // tipe order: dine_in, take_away

            // Diskon global
            $table->decimal('discount_value', 15, 2)->default(0); // nominal atau persen
            $table->enum('discount_type', ['nominal', 'percent'])->default('nominal');

            // Pembatalan
            $table->text('cancel_reason')->nullable(); // alasan pembatalan
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
