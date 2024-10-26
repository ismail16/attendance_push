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
        // dd($request->users_type);
        $remoteServerUrl = env('REMOTE_SERVER_URL');

        $response = Http::post($remoteServerUrl . '/api/get-device-users', [
            'api_key' => 123,
        ]);

        //dd responses users

        if (isset($response->json()['users'])) {
            // dd($response->json()['users']);

            //  $users = $response->json()['users'];
            //   $device = Device::findOrFail($request->device_id);
            //  $zk = new ZKTeco($device->device_ip, 4370);
            // $zk->connect();

            // if ($zk->connect()) {
            //     foreach ($users as $user) {
            //         //uid, userid, name, role, password, cardno
            //         $user_id = $user['id'];
            //         $user_name = substr($user['u_id'], 0, 2) . '-' . $user['name'];
            //         $role_id = 48;
            //         $user_phone = $user['phone'];
            //         $user_cardno = $user['cardno'] ?? 0;

            //         $zk->setUser($user_id, $user_id, $user_name, $role_id, $user_phone, $user_cardno);
            //     }
            // } else {
            // }
        } else {
        }
    }
}
