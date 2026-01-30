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
        Schema::table('pledges', function (Blueprint $table) {
            // Remove old fields (items, quantity will move to pledge_items)
            $table->dropColumn(['items', 'quantity']);
            
            // Add type field for future extensibility
            $table->enum('pledge_type', ['in-kind', 'financial'])->default('in-kind')->after('reference_number');
            
            // For manual financial tracking
            $table->decimal('financial_amount', 12, 2)->nullable()->after('pledge_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pledges', function (Blueprint $table) {
            $table->dropColumn(['pledge_type', 'financial_amount']);
            
            // Restore old fields
            $table->json('items')->nullable();
            $table->integer('quantity')->default(1);
        });
    }
};
