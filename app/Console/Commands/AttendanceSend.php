<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Rats\Zkteco\Lib\ZKTeco;

class AttendanceSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:send';

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
        $org_api = Organization::first();
        $final_attendances = [];
        $final_attendances = Attendance::where('status', 0)
            ->select('id', 'deviceId', 'punchMode', 'punchTime', 'punchType', 'userId')
            ->get()
            ->toArray();

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
    }
}
