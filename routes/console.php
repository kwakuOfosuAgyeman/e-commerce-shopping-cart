<?php

use App\Jobs\SendDailySalesReport;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
|
| Here you may define all of your scheduled tasks. Laravel's scheduler
| can handle a variety of task types (closures, console commands, etc).
|
*/

// Send daily sales report every evening at 9 PM
Schedule::job(new SendDailySalesReport())
    ->dailyAt('21:00')
    ->name('daily-sales-report')
    ->withoutOverlapping()
    ->onOneServer()
    ->emailOutputOnFailure(config('mail.admin_email', 'admin@example.com'));
