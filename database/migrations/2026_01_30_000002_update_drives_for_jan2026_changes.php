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
            // Cover photo field
            $table->string('cover_photo')->nullable()->after('description');
            
            // Pack types needed (JSON array of pack types)
            $table->json('pack_types_needed')->nullable()->after('items_needed');
            
            // Families affected (for auto-calculation)
            $table->integer('families_affected')->nullable()->after('pack_types_needed');
            
            // Track pledged vs distributed amounts separately
            $table->decimal('pledged_amount', 12, 2)->default(0)->after('collected_amount');
            $table->decimal('distributed_amount', 12, 2)->default(0)->after('pledged_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drives', function (Blueprint $table) {
            $table->dropColumn([
                'cover_photo',
                'pack_types_needed',
                'families_affected',
                'pledged_amount',
                'distributed_amount',
            ]);
        });
    }
};
