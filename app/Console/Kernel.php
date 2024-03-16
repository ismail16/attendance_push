<?php

namespace App\Console;

use App\Console\Commands\AttendanceStore;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        AttendanceStore::class,
        // AttendanceStore::class,
        // AttendanceStore::class
    ];

    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('attendance:store')->everyMinute();
        $schedule->command('user:send')->everyMinute();
        // $schedule->command('staff_attendance:send')->everyMinute();
        // $schedule->command('attendance:send')->everyMinute();
        // $schedule->command('attendance:send')->everyMinute();
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
