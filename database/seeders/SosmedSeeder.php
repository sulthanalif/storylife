<?php

namespace Database\Seeders;

use App\Models\Sosmed;
use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SosmedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = Status::where('name', 'Active')->first();
        $sosmeds = [
            ['name' => 'Instagram', 'link' => 'https://www.instagram.com/story.lifecreator_/', 'icon' => '<i class="fa-brands fa-instagram"></i>'],
            ['name' => 'Youtube', 'link' => 'https://www.youtube.com/', 'icon' => '<i class="fa-brands fa-youtube"></i>'],
        ];

        foreach ($sosmeds as $sosmed) {
            $sosmed['status_id'] = $status->id;
            Sosmed::create($sosmed);
        }
    }
}
