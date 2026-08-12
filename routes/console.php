<?php

use App\Jobs\ExpireZbotQueries;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Expirar ZbotQueries vencidas y cancelar pedidos sin stock → cada minuto
Schedule::job(new ExpireZbotQueries)->everyMinute();
