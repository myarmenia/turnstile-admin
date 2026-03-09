<?php

use App\Jobs\RefreshSitemap;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new RefreshSitemap())
    ->dailyAt('03:00')
    ->withoutOverlapping()
    ->name('sitemap-refresh')
    ->description('Обновление sitemap каждый день в 3 часа ночи');
