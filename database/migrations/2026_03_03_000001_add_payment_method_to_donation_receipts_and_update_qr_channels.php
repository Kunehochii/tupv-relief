<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add payment_method to donation_receipts
        Schema::table('donation_receipts', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('message');
        });

        // Migrate existing qr_channels from flat array to labeled format
        // Old: ["path1.png", "path2.png"]
        // New: [{"path": "path1.png", "label": ""}, {"path": "path2.png", "label": ""}]
        $users = DB::table('users')->whereNotNull('qr_channels')->get();
        foreach ($users as $user) {
            $channels = json_decode($user->qr_channels, true);
            if (is_array($channels) && !empty($channels)) {
                // Check if already in new format (has 'path' key)
                $first = $channels[0] ?? null;
                if (is_string($first)) {
                    // Old format — convert
                    $newChannels = array_map(fn($path) => [
                        'path' => $path,
                        'label' => '',
                    ], $channels);
                    DB::table('users')->where('id', $user->id)->update([
                        'qr_channels' => json_encode(array_values($newChannels)),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('donation_receipts', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        // Revert qr_channels back to flat array
        $users = DB::table('users')->whereNotNull('qr_channels')->get();
        foreach ($users as $user) {
            $channels = json_decode($user->qr_channels, true);
            if (is_array($channels) && !empty($channels)) {
                $first = $channels[0] ?? null;
                if (is_array($first) && isset($first['path'])) {
                    $flatChannels = array_map(fn($ch) => $ch['path'], $channels);
                    DB::table('users')->where('id', $user->id)->update([
                        'qr_channels' => json_encode(array_values($flatChannels)),
                    ]);
                }
            }
        }
    }
};
