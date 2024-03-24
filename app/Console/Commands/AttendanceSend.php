<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Rats\Zkteco\Lib\ZKTeco;

class AttendanceSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send the data to server';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $remoteServerUrl = env('REMOTE_SERVER_URL');

        // // Validate if the URL is properly formatted
        // if (filter_var($remoteServerUrl, FILTER_VALIDATE_URL) === false) {
        //     $this->error('Invalid REMOTE_SERVER_URL format.');
        //     return;
        // }
        // Extract the host from the URL
        // $urlParts = parse_url($remoteServerUrl);
        // $host = $urlParts['host'];

        // // Check if the host is reachable
        // $hostIsReachable = $this->isHostReachable($host);

        // if (!$hostIsReachable) {
        //     $this->error("Could not resolve host: $host");
        //     return;
        // }

        $devices = Device::all();
        $org_api_key = Organization::first()->api_key;
        $final_attendances = [];
        $errors = [];

        foreach ($devices as $device) {
            if ($device->device_ip) {
                $zk = new ZKTeco($device->device_ip, 4370);
                if ($zk->connect()) {
                    $zk->disableDevice();
                    $attendances = $zk->getAttendance();
                    // return $attendances;

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
                    $errors[] = ['msg' => 'Device ' . $device->name . ' not connected. Set the correct IP.'];
                    continue;
                }
            } else {
                continue;
            }
        }
        // return response()->json($final_attendances);
        //sent final_attendances to another server
        $response = Http::post($remoteServerUrl . '/api/device-attendance', [
            'api_key' => 12345678,
            'attendances' => $final_attendances
        ]);

        if ($response->ok()) {
            $this->info('Attendance Storred Successfully!');
        } else {
            $this->info('Something went wrong!');
        }

        // $data_array = Attendance::where('status', 0)->get();
        // $new_array = [];
        // foreach ($data_array as $data)
        // {
        //     $this_data = [
        //         "userId" => $data->user_id,
        //         "punchTime" => $data->punch_time,
        //         "punchType" => "Finger",
        //         "deviceId" => $data->device_id,
        //         "punchMode" => $data->punch_mode
        //     ];
        //     array_push($new_array, $this_data);
        // }
        // $response = Http::acceptJson()->post(env('REMOTE_SERVER_URL').'/api/user-device-data',$new_array);
        // if($response->ok()){

        //     foreach($data_array as $data){
        //         Attendance::find($data->id)->update(['status' => 1]);
        //     }
        //     $this->info('Attendance Storred Successfully!');
        // }
        // else{
        //     $this->info('Something went wrong!');
        // }
    }
}
