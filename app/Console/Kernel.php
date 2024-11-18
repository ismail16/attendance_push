<?php

namespace App\Console;

// use App\Console\Commands\AttendanceStore;
use App\Console\Commands\AttendanceSend;
use App\Console\Commands\FetchUserFromLive;
use App\Console\Commands\UserSendToDevice;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // AttendanceStore::class,
        AttendanceSend::class,
        UserSendToDevice::class,
        FetchUserFromLive::class
    ];

    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('attendance:store')->everyMinute();
        $schedule->command('attendance:send')->everyMinute();
        // $schedule->command('user:send')->everyMinute();
        // $schedule->command('user:fetch')->everyMinute();
        $schedule->command('user:fetch')->weekly()->weeklyOn(2, '10:00');
    }


    // protected function schedule(Schedule $schedule): void
    // {
    //     $schedule->command('attendance:store')->dailyAt('10:00')->twiceDaily(22, 2);
    //     $schedule->command('attendance:send')->dailyAt('22:00')->twiceDaily(10, 2);
    // }


    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
