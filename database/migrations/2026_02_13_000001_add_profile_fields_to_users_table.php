<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('organization_name');
            $table->string('contact_numbers')->nullable()->after('bio');
            $table->json('qr_channels')->nullable()->after('contact_numbers');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'contact_numbers', 'qr_channels']);
        });
    }
};
