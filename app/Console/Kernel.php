<?php

namespace App\Console;

use App\Jobs\MonthlyReminderJob;
use App\Jobs\MoulinetteJob;
use App\Jobs\PruneResearchJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands
        = [
            //
        ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //        $schedule->job(new MoulinetteJob)->dailyAt('18:00');
        $schedule->job(new PruneResearchJob)->dailyAt('10:00');
//        $schedule->job(new MonthlyReminderJob)->everyMinute();
//        $schedule->job(new PruneResearchJob)->everyFiveMinutes();
//        $schedule->command('horizon:snapshot')->everyFiveMinutes();
//        $schedule->command('telescope:prune --hours=48')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        include base_path('routes/console.php');
    }
}
