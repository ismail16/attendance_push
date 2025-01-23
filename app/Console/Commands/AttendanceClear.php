<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Device;
use Illuminate\Console\Command;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AttendanceClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will clear the attendance and then clear the attendance from the device';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $devices = Device::all();

        foreach ($devices as $device) {
            if ($device->device_ip) {
                try {
                    $zk = new ZKTeco($device->device_ip, 4370);

                    if ($zk->connect()) {
                        $zk->disableDevice();

                        $attendances = $zk->getAttendance();
                        $deviceAttendance = count($attendances);
                        $syncedAtt = Attendance::where('status', 1)->where('device_ip', $device->device_ip)->count();
                        if ($deviceAttendance == $syncedAtt) {
                            Log::warning("both are equals");
                        }
                    } else {
                        Log::error("Failed to connect to device with IP: {$device->device_ip}");
                    }
                } catch (\Exception $e) {
                    Log::error("Exception occurred while processing device.", [
                        'device_ip' => $device->device_ip,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                Log::warning("Device ID {$device->id} is missing an IP address.");
            }
        }
    }
}
