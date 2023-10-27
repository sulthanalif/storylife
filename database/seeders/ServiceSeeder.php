<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\Service;
use App\Helpers\EncodeFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = Status::where('name', 'Active')->first();
        $imagePath = base_path('public/upload/service.jpg');

        // if (File::exists($imagePath)) {
        //     $imageName = basename($imagePath);
        //     $imageData = EncodeFile::encodeFile($imageName);
        // } else {
        //     $imageData = null; 
        // }

        $services = [
            [
                'name' => 'Peweding',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
            ],
            [
                'name' => 'Weding',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
            ],
            [
                'name' => 'Hunting',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
            ],
            [
                'name' => 'Sport',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
            ],
            [
                'name' => 'Graduation',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
            ],
            [
                'name' => 'Birthday',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
            ],
        ];

        foreach ($services as $service) {
            $service['status_id'] = $status->id;
            $service['image'] = $imagePath;
            Service::create($service);
        }
    }
}

