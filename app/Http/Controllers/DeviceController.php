<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Organization;
use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;
use Carbon\Carbon;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SearchAttendanceExport;
use App\Models\Attendance;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


class DeviceController extends Controller
{

    public function index()
    {
        $devices = Device::all();

        return view('pages.devices.index', compact('devices'));
    }


    public function create()
    {
        return view('pages.devices.create');
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'device_id' => 'required|unique:devices,device_id',
            'name' => 'required|unique:devices,name',
            'device_ip' => 'required|unique:devices,device_ip'
        ]);

        Device::create($validatedData);

        return redirect()->route('devices.index')->with('success', 'Device Created Successfully!');
    }

    public function show(Device $device)
    {


        $device_info = array();
        if ($device->device_ip) {
            $device_info['deviceip'] = $device->device_ip;
            $zk = new ZKTeco($device->device_ip, 4370);

            if ($zk->connect()) {
                $zk->disableDevice();
                $device_info['deviceVersion'] = $zk->version();
                $device_info['deviceOSVersion'] = $zk->osVersion();
                $device_info['devicePlatform'] = $zk->platform();
                $device_info['devicefmVersion'] = $zk->fmVersion();
                $device_info['deviceworkCode'] = $zk->workCode();
                $device_info['devicessr'] = $zk->ssr();
                $device_info['devicepinWidth'] = $zk->pinWidth();
                $device_info['deviceserialNumber'] = $zk->serialNumber();
                $device_info['devicedeviceName'] = $zk->deviceName();
                $device_info['devicegetTime'] = $zk->getTime();
            } else {
                return redirect()->back()->with('error', 'Device' . $device->name . ' not connected. Set the correct IP.');
            }
        }

        return view('pages.devices.show', compact('device', 'device_info'));
    }

    public function edit(Device $device)
    {
        return view('pages.devices.edit', compact('device'));
    }


    public function update(Request $request, Device $device)
    {
        $validatedData = $request->validate([
            'device_id' => 'required|unique:devices,device_id,' . $device->id,
            'name' => 'required|unique:devices,name,' . $device->id,
            'device_ip' => 'required|unique:devices,device_ip,' . $device->id,
            'status' => 'nullable'
        ]);

        $validatedData['status'] = $request->status ?? 0;

        $device->update($validatedData);


        return redirect()->route('devices.index')->with('success', 'Device Updated Successfully!');
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Device Deleted Successfully!');
    }


    public function test_sound($id)
    {
        $device = Device::findOrFail($id);
        if ($device->device_ip) {
            $zk = new ZKTeco($device->device_ip, 4370);
            $zk->connect();
            $zk->disableDevice();
            $zk->testVoice();
            return redirect()->back()->with('success', 'Playing sound on device.');
        } else {
            return redirect()->back()->with('error', 'Device' . $device->name . ' not connected. Set the correct IP.');
        }
    }

    public function restart($id)
    {
        $device = Device::findOrFail($id);
        if ($device->device_ip) {
            $zk = new ZKTeco($device->device_ip, 4370);
            $zk->connect();
            $zk->disableDevice();
            $zk->restart();
            return redirect()->back()->with('success', 'Device restart successfully.');
        } else {
            return redirect()->back()->with('error', 'Device' . $device->name . ' not connected. Set the correct IP.');
        }
    }


    public function shutdown($id)
    {
        $device = Device::findOrFail($id);
        if ($device->device_ip) {
            $zk = new ZKTeco($device->device_ip, 4370);
            $zk->connect();
            $zk->disableDevice();
            $zk->shutdown();
            return redirect()->back()->with('success', 'Device is turning off.');
        } else {
            return redirect()->back()->with('error', 'Device' . $device->name . ' not connected. Set the correct IP.');
        }
    }


    public function attendance()
    {

        $devices = Device::all();
        $org_api_key = Organization::first()->api_key;
        $final_attendances = array();
        $errors = [];

        foreach ($devices as $device) {
            if ($device->device_ip) {
                $zk = new ZKTeco($device->device_ip, 4370);

                if ($zk->connect()) {
                    $zk->disableDevice();
                    $attendances = $zk->getAttendance();

                    // return $attendances;


                    foreach ($attendances as &$item) {
                        $item['device_id'] = $device->device_id;
                        $item['api_key'] = $org_api_key;
                    }

                    foreach ($attendances as $att) {
                        $final_attendances[] = $att;
                    }
                } else {
                    $errors[] = ['msg' => 'Device' . $device->name . ' not connected. Set the correct IP.'];
                    continue;
                }
            } else {
                continue;
            }
        }

        return view('pages.attendance.index', compact('final_attendances', 'errors'));
    }


    public function export()
    {
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

                    foreach ($attendances as &$item) {
                        $item['device_id'] = $device->device_id;
                        $item['api_key'] = $org_api_key;
                    }

                    foreach ($attendances as $att) {
                        $final_attendances[] = $att;
                    }
                } else {
                    $errors[] = ['msg' => 'Device ' . $device->name . ' not connected. Set the correct IP.'];
                    continue;
                }
            } else {
                continue;
            }
        }

        return $final_attendances;

        return Excel::download(new AttendanceExport($final_attendances), 'attendances.xlsx');
    }

    public function export_attendance()
    {

        $devices = Device::all();
        $org_api_key = Organization::first()->api_key;
        $finalAttendances = array();
        $errors = [];

        foreach ($devices as $device) {
            if ($device->device_ip) {
                $zk = new ZKTeco($device->device_ip, 4370);

                if ($zk->connect()) {
                    $zk->disableDevice();
                    $attendances = $zk->getAttendance();

                    foreach ($attendances as $att) {
                        $finalAtt['uid'] = $att['uid'];
                        $finalAtt['id'] = $att['id'];
                        $finalAtt['state'] = $att['state'];
                        $finalAtt['timestamp'] = $att['timestamp'];
                        $finalAtt['type'] = $att['type'];
                        $finalAtt['device_id'] = $device->device_id;
                        $finalAtt['api_key'] = $org_api_key;
                        $finalAttendances[] = $finalAtt;
                    }
                } else {
                    $errors[] = ['msg' => 'Device' . $device->name . ' not connected. Set the correct IP.'];
                    continue;
                }
            } else {
                continue;
            }
        }
        //dd($finalAttendances);
        // return $finalAttendances;

        return view('pages.attendance.export', compact('finalAttendances', 'errors'));
    }




    public function searchAttendance(Request $request)
    {
        try {

            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');


            $devices = Device::all();
            $orgApiKey = Organization::first()->api_key;
            $finalAttendances = [];

            $errors = [];

            foreach ($devices as $device) {
                if ($device->device_ip) {
                    $zk = new ZKTeco($device->device_ip, 4370);

                    if ($zk->connect()) {
                        $zk->disableDevice();
                        $attendances = $zk->getAttendance();

                        foreach ($attendances as &$item) {
                            // Check if the timestamp is within the specified date range
                            $attendanceDate = Carbon::parse($item['timestamp'])->toDateString();
                            if ($attendanceDate >= $startDate && $attendanceDate <= $endDate) {
                                $item['device_id'] = $device->device_id;
                                $item['api_key'] = $orgApiKey;
                                $finalAttendances[] = $item;
                            }
                        }
                    } else {
                        $errors[] = ['msg' => 'Device ' . $device->name . ' not connected. Set the correct IP.'];
                    }
                }
            }



            return view('pages.attendance.export', compact('finalAttendances', 'errors'));
        } catch (\Exception $e) {

            \Log::error('Error in searchAttendance: ' . $e->getMessage());


            return view('pages.error')->with('error', 'An error occurred while processing your request.');
        }
    }



    public function exportAttendances(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $devices = Device::all();
        $orgApiKey = Organization::first()->api_key;
        $finalAttendances = [];

        $errors = [];

        foreach ($devices as $device) {
            if ($device->device_ip) {
                $zk = new ZKTeco($device->device_ip, 4370);

                if ($zk->connect()) {
                    $zk->disableDevice();
                    $attendances = $zk->getAttendance();

                    foreach ($attendances as &$item) {
                        // Check if the timestamp is within the specified date range
                        $attendanceDate = Carbon::parse($item['timestamp'])->toDateString();
                        if ($attendanceDate >= $start_date && $attendanceDate <= $end_date) {
                            $item['device_id'] = $device->device_id;
                            $item['api_key'] = $orgApiKey;
                            $finalAttendances[] = $item;
                        }
                    }
                } else {
                    $errors[] = ['msg' => 'Device ' . $device->name . ' not connected. Set the correct IP.'];
                }
            }
        }

        return Excel::download(new AttendanceExport($finalAttendances), 'attendances.xlsx');
    }

    public function syncFromDevice()
    {
        $remoteServerUrl = env('REMOTE_SERVER_URL');

        // Validate if the URL is properly formatted
        if (filter_var($remoteServerUrl, FILTER_VALIDATE_URL) === false) {
            $this->error('Invalid REMOTE_SERVER_URL format.');
            return;
        }

        // Extract the host from the URL
        $urlParts = parse_url($remoteServerUrl);
        $host = $urlParts['host'];

        // Check if the host is reachable
        $hostIsReachable = $this->isHostReachable($host);

        if (!$hostIsReachable) {
            $this->error("Could not resolve host: $host");
            return;
        }

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

                    foreach ($attendances as $attendance) {
                        $formattedData = [
                            "userId" => $attendance['uid'],
                            "punchTime" => $attendance['timestamp'],
                            "punchType" => "Finger",
                            "deviceId" => $device->device_id,
                            "punchMode" => $attendance['type']
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


        // $arrs =  [
        //     'api_key' => $org_api_key,
        //     'attendances' => $final_attendances
        // ];



        // return  $time;

        // return Attendance::get();


        // Send formatted attendance data to the remote server
        // $response = Http::post($remoteServerUrl . '/api/device-attendance', [
        //     'attendances' => $final_attendances,
        //     'api_key' => $org_api_key,
        // ]);

        $response = Http::post('http://127.0.0.1:8000/api/device-attendance', [
            'api_key' => $org_api_key,
            'attendances' => $final_attendances
        ]);

        return  $response;

        if ($response->successful()) {
            Session::flash('success', 'Attendance data sent successfully from devices.');
        } else {
            Session::flash('error', 'Failed to send attendance data from devices.');
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                Session::flash('error', $error['msg']);
            }
        }

        $devices = Device::orderBy('device_id')->get();
        $all_users = array();
        $errors = [];

        foreach ($devices as $device) {
            if ($device->device_ip) {
                $zk = new ZKTeco($device->device_ip, 4370);

                if ($zk->connect()) {
                    $zk->disableDevice();
                    $users = $zk->getUser();

                    foreach ($users as &$user) {
                        $user['device_id'] = $device->device_id;
                    }

                    foreach ($users as $user) {
                        $all_users[] = $user;
                    }
                } else {
                    $errors[] = ['msg' => 'The device ' . $device->name . ' is not connected. Please set the ip correctly!'];
                    continue;
                }
            } else {
                continue;
            }
        }
        $user_count = count($all_users);
        $device_count = Device::count();
        $todays_attendance = Attendance::whereDate('punch_time', today()->format('Y-m-d'))->count();
        $org_name = Organization::first()->name;

        return view('dashboard', compact('user_count', 'device_count', 'todays_attendance', 'org_name'));
    }

    private function isHostReachable($host)
    {
        $ip = gethostbyname($host);

        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
}
