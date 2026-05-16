<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test {to? : Recipient email address}', function () {
    $to = (string) ($this->argument('to') ?: env('MAIL_USERNAME', ''));

    if ($to === '') {
        $this->error('No recipient provided. Pass one: php artisan mail:test you@example.com');
        return self::FAILURE;
    }

    Mail::raw('Laravel SMTP test email.', function ($message) use ($to) {
        $message->to($to)->subject('Laravel SMTP test');
    });

    $this->info('Mail send attempted (check inbox/spam, and storage/logs for errors).');
    return self::SUCCESS;
})->purpose('Send a test email via configured mailer');
