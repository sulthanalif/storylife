<?php

namespace Database\Seeders;

use App\Models\StatusOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'Success'],
            ['name' => 'Paid'],
            ['name' => 'Pending'],
            ['name' => 'Canceled'],
        ];

        foreach ($datas as $data) {
            StatusOrder::create($data);
        }
    }
}
