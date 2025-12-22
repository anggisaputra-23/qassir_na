<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('cash_closings', function (Blueprint $table) {
        // Cek dulu apakah kolom sudah ada
        if (!Schema::hasColumn('cash_closings', 'cash_opening_id')) {
            $table->unsignedBigInteger('cash_opening_id')->nullable()->after('id');
        }
    });
}


public function down()
{
    Schema::table('cash_closings', function (Blueprint $table) {
        $table->dropForeign(['cash_opening_id']);
        $table->dropColumn('cash_opening_id');
    });
}

};
