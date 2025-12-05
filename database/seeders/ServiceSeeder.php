<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'hair cut','created_at' => now(),'updated_at' => now()],
            ['name' => 'shaving','created_at' => now(),'updated_at' => now()],
            ['name' => 'hairdresser','created_at' => now(),'updated_at' => now()],
        ];

        Service::insert($data);
    }
}
