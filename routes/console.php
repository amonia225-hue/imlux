<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sauvegarde quotidienne de la base (nécessite un cron : * * * * * php artisan schedule:run)
Schedule::command('db:backup')->dailyAt('02:00');

// Rappels d'échéances aux clients (chaque matin)
Schedule::command('notifications:rappels')->dailyAt('08:00');
