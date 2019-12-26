<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\clearcredit::class,
        Commands\ReminderCron::class,
        Commands\SubscriptionAutoPay::class,
        Commands\currency_rate_update::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        /*$schedule->command('credit:clearing')
            ->daily();*/
        $schedule->command('credit:clearing')
            ->everyMinute();
        $schedule->command('reminder:cron')
            ->everyMinute();
        $schedule->command('subscription:autopay')
            ->everyMinute();
        $schedule->command('currency:rates')
            ->daily();
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
