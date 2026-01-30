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
        Schema::create('drive_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drive_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->decimal('quantity_needed', 12, 2);
            $table->decimal('quantity_pledged', 12, 2)->default(0);
            $table->decimal('quantity_distributed', 12, 2)->default(0);
            $table->string('unit');
            $table->string('pack_type')->nullable(); // Which pack this belongs to
            $table->boolean('is_custom')->default(false); // Admin manually added
            $table->timestamps();
            
            $table->index(['drive_id', 'item_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drive_items');
    }
};
