<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call(AdminSeeder::class);
        Organization::create([
            'name' => 'Demo Org',
            'api_key' => 'aXZ8qp7UI8lJr7Z'
        ]);
    }
}
