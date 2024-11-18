<?php

namespace App\Console\Commands;

use App\Models\Device;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Rats\Zkteco\Lib\ZKTeco;

class FetchUserFromLive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $remoteServerUrl = env('REMOTE_SERVER_URL');

        $response = Http::post($remoteServerUrl . '/api/get-device-users', [
            'api_key' => 123
        ]);
        //return $response;
        //dd responses users

        if (isset($response->json()['users'])) {
            //return ($response->json()['devices']);

            $users = $response->json()['users'];
            $devices = $response->json()['devices'];

            foreach ($devices as $device) {
                // return ($device);
                $zk = new ZKTeco($device['ip'], 4370);
                // $device_info = Device::where('device_ip', $device['ip'])->first();
                // return $device_info;
                $zk->connect();
                //  return $zk->serialNumber();
                if ($zk->connect()) {
                    foreach ($users as $user) {
                        $prefix = substr($user['u_id'], 0, 2);
                        $user_id = $user['id'];
                        $user_name = $prefix . '-' . $user['name'];
                        $role_id = 48;
                        $user_phone = $user['phone'];
                        $user_cardno = $user['cardno'] ?? 0;

                        // Define acceptable prefixes for each user type
                        $userTypes = [
                            'SM' => ['SM'],
                            'SF' => ['SF'],
                            'SMF' => ['SM', 'SF'],
                            'EM_TMF' => ['EM', 'TM', 'TF'],
                            'EM_TMF_SM' => ['EM', 'TM', 'TF', 'SM'],
                            'EM_TMF_SF' => ['EM', 'TM', 'TF', 'SF'],
                            'EM_TMF_SMF' => ['EM', 'TM', 'TF', 'SM', 'SF']
                        ];

                        // Check if the current user's prefix is allowed for the device's user type
                        if (isset($userTypes[$device['users_type']]) && in_array($prefix, $userTypes[$device['users_type']])) {
                            $zk->setUser($user_id, $user_id, $user_name, $role_id, $user_phone, $user_cardno);
                        }
                    }
                } else {
                    // return redirect()->back()->with('error', 'Device' . $device->name . ' not connected. Set the correct IP.');
                }
            }
        } else {
            // return redirect()->back()->with('error', 'No users found.');
        }
        // return redirect()->route('users.index')->with('success', 'Users imported successfully.');
    }
}
