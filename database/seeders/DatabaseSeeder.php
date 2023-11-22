<?php

namespace Database\Seeders;

// use App\Models\Gallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            StatusSeeder::class,
            StatusOrderSeeder::class,
            ServiceSeeder::class,
            SosmedSeeder::class,
            ReviewSeeder::class,
            GalerySeeder::class,
            OrderSeeder::class,
        ]);
    }
}
