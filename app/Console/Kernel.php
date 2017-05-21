<?php

namespace App\Console;

use App\Custom\Notification;
use App\Alarm;
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
            $notifications = Alarm::where(['time' => date('H:i')])->get();

            foreach ($notifications as $notification) {
                $profile = $notification->profile;

                $notification = new Notification("Laten we weer beginnen!", '{first_name} het is weer tijd om je gegevens in te vullen!');
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
