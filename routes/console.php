<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule pledge expiry tasks
Schedule::command('pledges:warn-expiry')->hourly();
Schedule::command('pledges:expire')->hourly();
