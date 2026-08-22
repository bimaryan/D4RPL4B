<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Http;
use App\Models\HostingCronJob;

Schedule::call(function () {
    $jobs = HostingCronJob::where('status', 'active')->get();
    
    foreach ($jobs as $job) {
        $shouldRun = false;
        
        switch ($job->interval) {
            case 'everyMinute':
                $shouldRun = true;
                break;
            case 'everyFiveMinutes':
                $shouldRun = now()->minute % 5 === 0;
                break;
            case 'hourly':
                $shouldRun = now()->minute === 0;
                break;
            case 'daily':
                $shouldRun = now()->hour === 0 && now()->minute === 0;
                break;
        }

        if ($shouldRun) {
            try {
                Http::timeout(10)->get($job->url);
                $job->update(['last_run' => now()]);
            } catch (\Exception $e) {
                // Biarkan fail, cron job akan dicoba lagi nanti
            }
        }
    }
})->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
