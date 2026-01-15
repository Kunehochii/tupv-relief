<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pledges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('drive_id')->constrained()->onDelete('cascade');
            $table->string('reference_number')->unique();
            $table->json('items')->nullable();
            $table->integer('quantity')->default(1);
            $table->text('details')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'verified', 'expired', 'distributed'])->default('pending');
            
            // Verification tracking
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('distributed_at')->nullable();
            
            // Impact feedback (admin input)
            $table->integer('families_helped')->nullable();
            $table->integer('relief_packages')->nullable();
            $table->integer('items_distributed')->nullable();
            $table->text('admin_feedback')->nullable();
            
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index('reference_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pledges');
    }
};
