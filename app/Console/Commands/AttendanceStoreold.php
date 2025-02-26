<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\Organization;
use Illuminate\Console\Command;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\Http;


class AttendanceStoreold extends Command
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
                $zk = new ZKTeco($device->device_ip, 4370);

                if ($zk->connect()) {
                    $zk->disableDevice();
                    $attendances = $zk->getAttendance();
                    // $zk->clearAttendance();

                    // foreach ($attendances as &$item) {
                    //     $item['device_id'] = $device->device_id;
                    //     $item['api_key'] = $org_api_key;
                    // }

                    // foreach ($attendances as $att) {
                    //     $final_attendances[] = $att;
                    // }

                    foreach ($attendances as $attendance) {
                        $formattedData = [
                            "userId" => $attendance['id'],
                            "punchTime" => $attendance['timestamp'],
                            "punchType" => $attendance['type'],
                            "deviceId" => $device->device_id,
                            "punchMode" => "Finger"
                        ];
                        $final_attendances[] = $formattedData;
                    }
                } else {
                    continue;
                }
            } else {
                continue;
            }
        }

        $response = Http::post($remoteServerUrl . '/api/device-attendance', [
            'api_key' => 12345678,
            'attendances' => $final_attendances

        ]);



        // foreach ($final_attendances as $attend) {
        //     Attendance::create([

        //         'api_key' => $attend['api_key'],
        //         'user_id' => $attend['id'],
        //         'punch_time' => $attend['timestamp'],
        //         'device_id' => $attend['device_id'],
        //         'punch_mode' => ($attend['type'] == 0 || $attend['type'] == 4) ? 'IN' : 'OUT'

        //     ]);
        // }
    }
}
