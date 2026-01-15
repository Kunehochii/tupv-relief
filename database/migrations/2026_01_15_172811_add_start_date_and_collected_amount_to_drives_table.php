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
        Schema::table('drives', function (Blueprint $table) {
            $table->datetime('start_date')->nullable()->after('items_needed');
            $table->decimal('collected_amount', 12, 2)->default(0)->after('target_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drives', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'collected_amount']);
        });
    }
};
