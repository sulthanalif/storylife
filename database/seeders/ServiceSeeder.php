<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = Status::where('name', 'Active')->first();
        $services = [
            [
                'name' => 'Peweding',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
                'image' => NULL,
            ],
            [
                'name' => 'Weding',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
                'image' => NULL,
            ],
            [
                'name' => 'Hunting',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
                'image' => NULL,
            ],
            [
                'name' => 'Sport',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
                'image' => NULL,
            ],
            [
                'name' => 'Graduation',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
                'image' => NULL,
            ],
            [
                'name' => 'Birthday',
                'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga dignissimos, ratione excepturi maiores repellendus minus commodi eum earum vitae ullam voluptates. Ratione, culpa id! Molestias deleniti ipsa adipisci nostrum blanditiis.',
                'image' => NULL,
            ],
        ];

        foreach ($services as $service) {
            $service['status_id'] = $status->id;
            Service::create($service);
        }
    }
}

