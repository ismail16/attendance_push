<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Rats\Zkteco\Lib\ZKTeco;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendance = Attendance::whereDate('punch_time', today()->format('Y-m-d'))->get();
        return view('pages.data.index', compact('attendance'));
    }

    public function device_attendance_push(Request $request)
    {
        // $remoteServerUrl = env('REMOTE_SERVER_URL');

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
        $response = Http::post('http://127.0.0.1:8000/api/device-attendance', [
            'api_key' => 12345678,
            'attendances' => $final_attendances

        ]);
        return $response->json();

        // dd($response->json());
        //Code for Live Server
        // $last_att = Attendance::orderBy('punch_time', 'desc')->first();
        // if (!$last_att) {
        //     foreach ($final_attendances as $att) {
        //         $attData = new Attendance;

        //         $attData->api_key = $request->api_key;
        //         $attData->user_id = $att['userId'];
        //         $attData->punch_time = $att['punchTime'];
        //         $attData->device_id = $att['deviceId'];
        //         $attData->punch_mode = $att['punchType'];
        //         $attData->save();
        //         $message = "New  data Upload Successfully";
        //     }
        // } else {
        //     foreach ($final_attendances as $att) {
        //         if (strtotime($last_att->punch_time) < strtotime($att['punchTime'])) {
        //             $attData = new Attendance;
        //             $attData->api_key = $request->api_key;
        //             $attData->user_id = $att['userId'];
        //             $attData->punch_time = $att['punchTime'];
        //             $attData->device_id = $att['deviceId'];
        //             $attData->punch_mode = $att['punchType'];
        //             $attData->save();
        //             $message = "Update new data Successfully";
        //         } else {
        //             $message = "Already Updated all Data";
        //         }
        //     }
        // }


        //return response()->json(['success' => $message]);
    }
}
