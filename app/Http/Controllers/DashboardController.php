<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\Organization;
use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;

class DashboardController extends Controller
{
    public function index()
    {
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
                    $errors[] = ['msg' => 'The device '. $device->name . ' is not connected. Please set the ip correctly!'];
                    continue;
                }
            } else {
                continue;
            }
        }
        $user_count = count($all_users);
        $device_count = Device::count();
        $todays_attendance = Attendance::whereDate('punchTime', today()->format('Y-m-d'))->count();
        $org_name = Organization::first()->name;

        return view('dashboard', compact('user_count', 'device_count', 'todays_attendance', 'org_name'));
    }

  
}
