<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_openings', function (Blueprint $table) {
            if (!Schema::hasColumn('cash_openings', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('shift_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cash_openings', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
