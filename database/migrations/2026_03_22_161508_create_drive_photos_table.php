<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drive_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drive_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('drive_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drive_photos');
    }
};
