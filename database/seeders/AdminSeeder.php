<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'DSWD Admin',
            'email' => 'admin@relief.app',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'verification_status' => User::STATUS_VERIFIED,
            'email_verified_at' => now(),
            'otp_verified' => true,
        ]);

        User::create([
            'name' => 'DSWD Supervisor',
            'email' => 'supervisor@relief.app',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'verification_status' => User::STATUS_VERIFIED,
            'email_verified_at' => now(),
            'otp_verified' => true,
        ]);
    }
}
