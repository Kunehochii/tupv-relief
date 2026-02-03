<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing financial drives to in-kind
        DB::table('drives')
            ->where('target_type', 'financial')
            ->update(['target_type' => 'in-kind']);

        // Note: For MySQL, we would need raw SQL to change the enum
        // ALTER TABLE drives MODIFY target_type ENUM('in-kind') DEFAULT 'in-kind';
        // However, keeping both values in enum for backward compatibility and data integrity
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed - financial drives cannot be restored
        // as they were converted to in-kind
    }
};
