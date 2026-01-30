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
        Schema::create('relief_pack_items', function (Blueprint $table) {
            $table->id();
            $table->string('pack_type'); // food, kitchen, hygiene, sleeping, clothing
            $table->string('item_name');
            $table->decimal('quantity_per_family', 10, 2);
            $table->string('unit'); // kg, pcs, L, sachets, tins, packs, pairs, etc.
            $table->timestamps();
            
            $table->index('pack_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relief_pack_items');
    }
};
