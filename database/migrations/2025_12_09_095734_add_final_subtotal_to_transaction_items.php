<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('transaction_items', function (Blueprint $table) {
        $table->decimal('final_subtotal', 12, 2)->after('subtotal')->default(0);
    });
}

public function down()
{
    Schema::table('transaction_items', function (Blueprint $table) {
        $table->dropColumn('final_subtotal');
    });
}


};
