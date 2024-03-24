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
        // dd($request->users_type);
        $remoteServerUrl = env('REMOTE_SERVER_URL');

        $users_type = "EM_TMF";  //SM, SF, SMF, EM_TMF_SMF
        $device_id = 1; // 1, 2

        $response = Http::post($remoteServerUrl . '/api/get-users', [
            'api_key' => 123,
            'users_type' => $users_type, 
            'device_id' => $device_id, // 1, 2
        ]);
        //dd responses users

        if (isset($response->json()['users'])) {
            // dd($response->json()['users']);

            $users = $response->json()['users'];
            $device = Device::findOrFail($device_id);
            $zk = new ZKTeco($device->device_ip, 4370);
            $zk->connect();

            $message = [];

            if ($zk->connect()) {
                foreach ($users as $user) {
                    //uid, userid, name, role, password, cardno
                    $user_id = $user['id'];
                    $user_name = substr($user['u_id'], 0, 2) . '-' . $user['name'];
                    $role_id = 48;
                    $user_phone = $user['phone'];
                    $user_cardno = $user['cardno'] ?? 0;

                    $zk->setUser($user_id, $user_id, $user_name, $role_id, $user_phone, $user_cardno);
                }
            } else {
                $message[] = ['msg' => 'Device ' . $device->name . ' not connected. Set the correct IP.'];
            }
        } else {
            $message[] = ['msg' => 'No users found.'];
        }
        $message[] = ['msg' => 'Users imported successfully.'];

        if ($response->ok()) {
            $this->info('Attendance Storred Successfully!');
        } else {
            $this->info('Something went wrong!');
        }
    }
}
