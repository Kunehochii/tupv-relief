<?php

namespace App\Providers;

use App\Mail\SendGridTransport;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;

class MailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Mail::extend('sendgrid', function (array $config) {
            return new SendGridTransport(
                config('services.sendgrid.key')
            );
        });
    }
}
