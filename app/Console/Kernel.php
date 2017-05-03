<?php

namespace App\Console;

use App\Custom\Notification;
use App\Time;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            date_default_timezone_set('Europe/Amsterdam');

            $notifications = Time::where(['time' => date('H:i')])->get();

            foreach ($notifications as $notification) {
                $profile = $notification->profile;

                $notification = new Notification('Het is weer tijd!', '{first_name}! Het is weer tijd om een 
                spel te spelen');
                $notification->send($profile);
            }
        })->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
