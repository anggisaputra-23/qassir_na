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
        Schema::table('cash_openings', function (Blueprint $table) {
            $table->boolean('owner_closed_same_day')->default(false)->after('shift_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_openings', function (Blueprint $table) {
            $table->dropColumn('owner_closed_same_day');
        });
    }
};
