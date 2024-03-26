<?php

namespace App\Console\Commands;

use App\Models\user;
use App\Models\Device;
use Rats\Zkteco\Lib\ZKTeco;

use App\Models\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;


class UserSendToDevice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:send';

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
        // demo responce of "Device api call"
        // $server_devices = array( 
        //     array (
        //        "device_id" => 1,
        //        "device_ip" => "192.168.0.9",
        //        "users_type" => ["SM"], 
        //     ),
        //     array (
        //         "device_id" => 2,
        //         "device_ip" => "192.168.0.10",
        //         "users_type" => ["SM", "EM_TMF"], 
        //     )
        // ); 
        //API responce of devices

        $server_devices = Http::post($remoteServerUrl . '/api/get-attendance-device', [
            'api_key' => 12345678
        ]);

        foreach(json_decode($server_devices) as $server_device){

            $server_users = Http::post($remoteServerUrl . '/api/get-users', [
                'api_key' => 123,
                'users_type' => $server_device->users_type, 
                'device_id' => $server_device->id, // 1, 2
            ]);

            if ($server_users) {
                // dd($response->json()['users']);
    
                $users = $server_users;
                // $device = Device::where('device_ip', $server_device['device_ip'])->first();  used it earlier 
                $zk = new ZKTeco($server_device->ip, 4370);
                $zk->connect();
    
                if ($zk->connect()) {
                    foreach ($users as $user) {
                        //uid, userid, name, role, password, cardno
                        $user_id = $user['id'];
                        $user_name = substr($user['u_id'], 0, 2) . '-' . $user['name'];
                        $role_id = 0;
                        $user_phone = $user['phone'];
                        $user_cardno = $user['cardno'] ?? 0;
    
                        $zk->setUser($user_id, $user_id, $user_name, $role_id, $user_phone, $user_cardno);
                    }
                } else {
                    $this->info('Device' . $server_device->ip . ' not connected. Set the correct IP.');
                }
            } else {
                $this->info('No users found.');
            }
            $this->info('Users pull to divice successfully.');
        }

        // if ($response->ok()) {
        //     $this->info('Attendance Storred Successfully!');
        // } else {
        //     $this->info('Something went wrong!');
        // }
    }
}
