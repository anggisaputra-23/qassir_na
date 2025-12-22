<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('transaction_items', function (Blueprint $table) {
			if (!Schema::hasColumn('transaction_items', 'global_discount')) {
				$table->decimal('global_discount', 10, 2)->default(0)->after('discount');
			}
			if (!Schema::hasColumn('transaction_items', 'global_discount_type')) {
				$table->enum('global_discount_type', ['nominal', 'percent'])->default('nominal')->after('global_discount');
			}
		});
	}

	public function down(): void
	{
		Schema::table('transaction_items', function (Blueprint $table) {
			if (Schema::hasColumn('transaction_items', 'global_discount_type')) {
				$table->dropColumn('global_discount_type');
			}
			if (Schema::hasColumn('transaction_items', 'global_discount')) {
				$table->dropColumn('global_discount');
			}
		});
	}
};

