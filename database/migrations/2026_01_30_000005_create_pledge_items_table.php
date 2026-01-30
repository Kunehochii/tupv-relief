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
        Schema::create('pledge_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pledge_id')->constrained()->onDelete('cascade');
            $table->foreignId('drive_item_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name'); // Denormalized for display
            $table->decimal('quantity', 12, 2);
            $table->string('unit');
            
            // Distribution tracking per item
            $table->decimal('quantity_distributed', 12, 2)->default(0);
            $table->integer('families_helped')->default(0);
            $table->timestamp('distributed_at')->nullable();
            $table->timestamps();
            
            $table->index('pledge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pledge_items');
    }
};
