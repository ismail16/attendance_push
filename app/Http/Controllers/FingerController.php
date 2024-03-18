<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;

use App\Exports\UsersExport;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class FingerController extends Controller
{
    public function index()
    {
        $devices = Device::orderBy('device_id')->get();
        return view('pages.fingers.index', compact('devices'));
    }

    public function exportFNGToServer(Request $request)
    {
        // dd("No finger found");
        // return response()->json(['msg' => 'Exporting Fingers to server']);
        $remoteServerUrl = env('REMOTE_SERVER_URL');
        $devices = Device::all();
        foreach ($devices as $device) {
            $zk = new ZKTeco($device->device_ip, 4370);

            if ($zk->connect()) {
                $zk->disableDevice();
                $users = $zk->getUser();
                //return response()->json($users);
                // $last_user = end($users)['userid'];

                foreach ($users as $user) {
                    //dd($user);
                    // return response()->json($user);
                    //get users fingerprint
                    $userFingerprints = $zk->getFingerprint($user['uid']);
                    // dd($userFingerprints);
                    //dd index of fingerprint
                    //dd($userFingerprints[9]);

                    if (count($userFingerprints) > 0 && ($user['role'] == 0)) {
                        foreach ($userFingerprints as $index => $fingerprint) {
                            // dd($index);
                            // $base64 = base64_encode($fingerprint);
                            $base64Fingerprints[$index] = base64_encode($fingerprint);
                        }

                        $response = Http::post($remoteServerUrl . '/api/set-fingerprints', [
                            'api_key' => 12345678,
                            'id' => $user['uid'],
                            'fingerprints' => $base64Fingerprints,
                        ]);
                    }
                }
            } else {
                $errors[] = ['msg' => 'Device' . $device->name . ' not connected. Set the correct IP.'];
                continue;
            }
        }
        return $response->json();
        return response()->json(['msg' => 'Exporting Fingerprints to server']);
    }


    public function importFNGToDevice(Request $request)
    {
        $remoteServerUrl = env('REMOTE_SERVER_URL');

        $response = Http::post($remoteServerUrl . '/api/get-users', [
            'api_key' => 123,
            'users_type' => $request->users_type
        ]);

        //dd($response->json());
        foreach ($response->json()['users'] as $user) {
            //for user fingerprint
            //count user fingerprint
            // dd($user);
            $fingerprints = json_decode($user['fingerprints']);
            $data = [];
            foreach ($fingerprints as $index => $fingerprint) {

                $decodedFingerprint = base64_decode($fingerprint);
                //set fingerprint to device
                $data[$index] = $decodedFingerprint;
            }


            $device = Device::find($request->device_id);
            $zk = new ZKTeco($device->device_ip, 4370);

            if ($zk->connect()) {
                $zk->disableDevice();
                //Set fingerprint to device
                $result =  $zk->setFingerprint($user['id'], $data);
                dd($result);
            } else {
                $errors[] = ['msg' => 'Device' . $device->name . ' not connected. Set the correct IP.'];
                continue;
            }
            //dd($fingerprint);
            // dd($user['fingerprints']);
        }
        return response()->json(['msg' => 'Importing Fingerprints to Device']);
    }
}
