<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_openings', function (Blueprint $table) {
            if (!Schema::hasColumn('cash_openings', 'shift_number')) {
                $table->tinyInteger('shift_number')->default(1)->after('date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cash_openings', function (Blueprint $table) {
            $table->dropColumn('shift_number');
        });
    }
};
