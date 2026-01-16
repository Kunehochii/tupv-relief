<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule pledge expiry tasks
// Send expiry warnings daily at 8:00 AM
Schedule::command('pledges:warn-expiry')->dailyAt('08:00');

// Expire unverified pledges daily at midnight
Schedule::command('pledges:expire')->dailyAt('00:00');
