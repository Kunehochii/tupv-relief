<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('target_amount', 12, 2)->default(0);
            $table->enum('target_type', ['financial', 'quantity'])->default('quantity');
            $table->json('items_needed')->nullable();
            $table->datetime('end_date');
            $table->enum('status', ['active', 'completed', 'closed'])->default('active');
            
            // Location fields for OpenStreetMap
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address')->nullable();
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['status', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drives');
    }
};
