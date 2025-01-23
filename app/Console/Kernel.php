<?php

namespace App\Console;

use App\Console\Commands\AttendanceClear;
use App\Console\Commands\AttendanceSend;
use App\Console\Commands\AttendanceStore;
use App\Console\Commands\FetchUserFromLive;
use App\Console\Commands\UserSendToDevice;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        AttendanceStore::class,
        AttendanceSend::class,
        UserSendToDevice::class,
        FetchUserFromLive::class,
        AttendanceClear::class
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('attendance:store')->everyFiveMinutes();
        $schedule->command('attendance:send')->everyFiveMinutes();
        $schedule->command('attendance:clear')->hourly();
        $schedule->command('user:send')->everyMinute();
        //$schedule->command('user:fetch')->cron('0 10-12 * * *'); // From 10:00 AM to 11:00 PM
        $schedule->command('user:fetch')->weekly()->weeklyOn(2, '10:00');
    }




    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
