<?php

use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command("email_test", function () {
    Mail::to(User::first())->send(new TestMail());
})->purpose('Test the email system');
