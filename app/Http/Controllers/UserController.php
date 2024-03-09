<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;

use App\Exports\UsersExport;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

                    if (count($users) > 0) {
                        $last_user = end($users)['userid'];
                        foreach ($users as &$user) {
                            $user['device_id'] = $device->device_id;
                            if ($user['userid'] == $last_user) {
                                unset($user);
                            }
                        }

                        foreach ($users as $key => $user) {
                            $all_users[] = $user;
                        }
                    }
                } else {
                    $errors[] = ['msg' => 'Device' . $device->name . ' not connected. Set the correct IP.'];
                    continue;
                }
            } else {
                continue;
            }
        }

        return view('pages.users.index', compact('all_users', 'errors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $devices = Device::all();
        return view('pages.users.create', compact('devices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'name' => 'required',
        ]);

        $device = Device::findOrFail($request->device_id);
        $zk = new ZKTeco($device->device_ip, 4370);
        if ($zk->connect()) {

            $zk->disableDevice();
            $users = $zk->getUser();

            $last_uid = end($users)['uid'] ?? 0;
            $last_userid = end($users)['userid'] ?? 0;
            $uid =  $last_uid + 1;
            $userid = $last_userid + 1;
            $name = $request->name;
            $role = (int)$request->role ?? 0;
            $password = $request->password;
            $cardno = $request->cardno;
            $zk->setUser($uid, $userid, $name, $role, $password, $cardno);
            return redirect()->back()->with('success', 'User added to device successfully.');
        } else {
            return redirect()->back()->with('error', 'Device' . $device->name . ' not connected. Set the correct IP.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show_single(Request $request)
    {
        $device = Device::findOrFail($request->device_id);
        if ($device->device_ip) {
            $uid = $request->uid;
            $userid = $request->userid;
            $name = $request->name;
            $role = (int)$request->role;
            $password = $request->password;
            $cardno = $request->cardno;

            $zk = new ZKTeco($device->device_ip, 4370);
            if ($zk->connect()) {
                $userfingerprints = $zk->getFingerprint($request->uid);
                return view('pages.users.show', compact(
                    'uid',
                    'userid',
                    'name',
                    'role',
                    'password',
                    'cardno',
                    'userfingerprints',
                ));
            } else {
                return redirect()->back()->with('error', 'Device' . $device->name . ' not connected. Set the correct IP.');
            }
        } else {
            return redirect()->back()->with('error', 'Device' . $device->name . ' not connected. Set the correct IP.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uid, $device_id)
    {

        $device = Device::findOrFail($device_id);

        if ($device->device_ip) {
            $zk = new ZKTeco($device->device_ip, 4370);
            if ($zk->connect()) {
                $zk->disableDevice();
                $zk->removeUser($uid);
                return redirect()->back()->with('success', 'User removed from device successfully.');
            } else {
                return redirect()->back()->with('error', 'Device' . $device->name . ' not connected. Set the correct IP.');
            }
        } else {
            return redirect()->back()->with('error', 'Device' . $device->name . ' not connected. Set the correct IP.');
        }
    }



    public function export()
    {
        $devices = Device::orderBy('device_id')->get();
        $all_users = [];

        foreach ($devices as $device) {
            if ($device->device_ip) {
                $zk = new ZKTeco($device->device_ip, 4370);

                if ($zk->connect()) {
                    $zk->disableDevice();
                    $users = $zk->getUser();

                    if (count($users) > 0) {
                        $last_user = end($users)['userid'];
                        foreach ($users as &$user) {
                            $user['device_id'] = $device->device_id;
                            if ($user['userid'] == $last_user) {
                                unset($user);
                            }
                        }

                        foreach ($users as $key => $user) {
                            $all_users[] = $user;
                        }
                    }
                } else {
                    // Handle connection error
                    continue;
                }
            } else {
                // Handle missing device IP
                continue;
            }
        }

        return Excel::download(new UsersExport($all_users), 'users.xlsx');
    }

    public function show()
    {
    }

    public function importUsersIndex()
    {
        $devices = Device::all();
        // dd($devices);
        return view('pages.users.import',  compact('devices'));
    }

    public function importUsersFromServer(Request $request)
    {

        // dd($request->all());
        $response = Http::post('http://127.0.0.1:8000/api/get-users', [
            'api_key' => 123,
            'users_type' => $request->users_type
        ]);
        //dd responses users

        if (isset($response->json()['users'])) {
            // dd($response->json()['users']);
            $users = $response->json()['users'];
            foreach ($users as $user) {
                //dd($user);
                $device = Device::findOrFail($request->device_id);
                $zk = new ZKTeco($device->device_ip, 4370);
                if ($zk->connect()) {
                    $zk->disableDevice();
                    //uid, userid, name, role, password, cardno
                    $user['cardno'] = $user['cardno'] ?? 0;
                    $zk->setUser($user['id'], $user['u_id'], $user['name'], $user['role_id'], $user['phone'], $user['cardno']);
                } else {
                    return redirect()->back()->with('error', 'Device' . $device->name . ' not connected. Set the correct IP.');
                }
            }
        } else {
            return redirect()->back()->with('error', 'No users found.');
        }
        return back()->with('success', 'Users imported successfully.');
    }
}
