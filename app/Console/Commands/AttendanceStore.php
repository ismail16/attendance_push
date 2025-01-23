<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\Organization;
use Illuminate\Console\Command;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AttendanceStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will store the attendance and then clear the attendance from the device';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $remoteServerUrl = env('REMOTE_SERVER_URL');

        $devices = Device::all();
        $org_api_key = Organization::first()->api_key;
        $final_attendances = array();

        foreach ($devices as $device) {
            if ($device->device_ip) {
                try {
                    $zk = new ZKTeco($device->device_ip, 4370);

                    if ($zk->connect()) {
                        $zk->disableDevice();

                        $attendances = $zk->getAttendance();

                        foreach ($attendances as $attendance) {
                            try {
                                // Convert punch time to a consistent format (optional, if needed)
                                $formattedPunchTime = $attendance['timestamp']; // Assuming already in 'Y-m-d H:i:s'

                                // Check if the record already exists
                                $isDuplicate = Attendance::where('userId', $attendance['id'])
                                    ->where('punchTime', $formattedPunchTime)
                                    ->exists();

                                if (!$isDuplicate) {
                                    // Only store if no duplicate is found
                                    Attendance::create([
                                        'api_key' => $org_api_key,
                                        'userId' => $attendance['id'],
                                        'punchTime' => $formattedPunchTime,
                                        "deviceId" => $device->device_id,
                                        "device_ip" => $device->device_ip,
                                        'punchType' => $attendance['type'],
                                        "punchMode" => "Finger"
                                    ]);
                                }
                            } catch (\Exception $e) {
                                Log::error('Failed to create attendance record.', [
                                    'userId' => $attendance['id'] ?? null,
                                    'punchTime' => $formattedPunchTime ?? null,
                                    'device_ip' => $device->device_ip,
                                    'error' => $e->getMessage()
                                ]);
                            }
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
