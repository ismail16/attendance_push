<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'mobile' => '01900000000',
            'device_name' => 'ZKTeco iclock-9000',
            'device_sl' => 'JR09234234',
            'password' => bcrypt('11111111'),
        ]);
    }
}
