<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_closings', function (Blueprint $table) {
            // Cek dulu apakah foreign key dan kolom masih ada
            if (Schema::hasColumn('cash_closings', 'opening_id')) {

                // Hapus foreign key kalau ada
                try {
                    $table->dropForeign(['opening_id']);
                } catch (\Exception $e) {
                    // Abaikan jika FK tidak ditemukan
                }

                // Sekarang aman untuk drop kolom
                $table->dropColumn('opening_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cash_closings', function (Blueprint $table) {
            if (!Schema::hasColumn('cash_closings', 'opening_id')) {
                $table->unsignedBigInteger('opening_id')->nullable()->after('id');
                $table->foreign('opening_id')->references('id')->on('cash_openings')->onDelete('set null');
            }
        });
    }
};
