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
        $attendance = Attendance::get();
        return view('pages.data.index', compact('attendance'));
    }

    public function device_attendance_pushed(Request $request)
    {
        $remoteServerUrl = env('REMOTE_SERVER_URL');


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
        return $response->json();
    }

    public function device_attendance_push(Request $request)
    {
        $devices = Device::all();
        $org_api = Organization::first();
        $final_attendances = [];
        $errors = [];
        $responses = [];
        $batchSize = 100; // Number of records per batch

        // foreach ($devices as $device) {
        //     if ($device->device_ip) {
        //         $zk = new ZKTeco($device->device_ip, 4370);

        //         if ($zk->connect()) {
        //             $zk->disableDevice();
        //             $attendances = $zk->getAttendance();

        //             foreach ($attendances as $attendance) {
        //                 $formattedData = [
        //                     "userId" => $attendance['id'],
        //                     "punchTime" => $attendance['timestamp'],
        //                     "punchType" => $attendance['type'],
        //                     "deviceId" => $device->device_id,
        //                     "punchMode" => "Finger"
        //                 ];
        //                 $final_attendances[] = $formattedData;
        //             }
        //         } else {
        //             $errors[] = ['msg' => 'Device ' . $device->name . ' not connected. Set the correct IP.'];
        //             continue;
        //         }
        //     } else {
        //         continue;
        //     }
        // }


        // return $final_attendances;
        // Send data to remote server in batches
        $final_attendances = Attendance::where('status', 0)
            ->select('id', 'deviceId', 'punchMode', 'punchTime', 'punchType', 'userId')
            ->get()
            ->toArray();

        // return $final_attendances;
        // $chunks = array_chunk($final_attendances, 100);

        $chunks = array_chunk($final_attendances, 100);
        //return $chunks;
        foreach ($chunks as $batch) {
            $response = Http::post($org_api->url . '/api/device-attendance', [
                'api_key' => $org_api->api_key,
                'attendances' => $batch,
            ]);

            if ($response->successful()) {
                $responseData = $response->json(); // Get the response body as an array

                // Check if the response contains a 'message' key and handle accordingly
                if (isset($responseData['success']) && !empty($responseData['success'])) {
                    $responses[] = $responseData; // Add the response to the array
                    $attendanceIds = array_column($batch, 'id'); // Assuming 'id' is the identifier for attendance records
                    Attendance::whereIn('id', $attendanceIds)->update(['status' => 1]);
                } else {
                    $responses[] = [
                        'error' => true,
                        'message' => 'Successful response, but no success message found.',
                    ];
                }
            } else {
                $responses[] = [
                    'error' => true,
                    'message' => 'Failed to push batch.',
                    'details' => $response->json(),
                ];
            }
        }

        return response()->json([
            'message' => 'Data processed successfully.',
            'responses' => $responses,
            'errors' => $errors,
        ]);
    }
}
