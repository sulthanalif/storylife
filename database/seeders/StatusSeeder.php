<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Active',
            'description' => ''],
            ['name' => 'Draft',
            'description' => ''],
            ['name' => 'Pending',
            'description' => ''],
            ['name' => 'Blocked',
            'description' => ''],
        ];

        foreach ($statuses as $data) {
            Status::create($data);
        }
    }
}
