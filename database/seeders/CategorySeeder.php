<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Preweding', 'description' => 'This is Description'],
            ['name' => 'Weding', 'description' => 'This is Description'],
            ['name' => 'Hunting', 'description' => 'This is Description'],
            ['name' => 'Sport', 'description' => 'This is Description'],
            ['name' => 'Graduation', 'description' => 'This is Description'],
            ['name' => 'Birthday', 'description' => 'This is Description'],
        ];

        foreach ($categories as $data) {
            Category::create($data);
        }
    }
}
