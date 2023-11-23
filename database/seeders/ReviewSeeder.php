<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Review;
use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('name', 'Sulthan Alif Hayatyo')->first();
        $category = Category::where('name', 'Hunting')->first();
        $status = Status::where('name', 'Active')->first();

        $datas = [
            ['rating' => 5,
            'comment' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. At tempora autem qui dicta ex itaque natus quidem quasi, iste repellat alias doloribus aspernatur? Esse dolorem sint itaque, officiis nisi accusantium!
            '],
            ['rating' => 3,
            'comment' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. At tempora autem qui dicta ex itaque natus quidem quasi, iste repellat alias doloribus aspernatur? Esse dolorem sint itaque, officiis nisi accusantium!
            '],
            ['rating' => 4,
            'comment' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. At tempora autem qui dicta ex itaque natus quidem quasi, iste repellat alias doloribus aspernatur? Esse dolorem sint itaque, officiis nisi accusantium!
            '],
            ['rating' => 5,
            'comment' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. At tempora autem qui dicta ex itaque natus quidem quasi, iste repellat alias doloribus aspernatur? Esse dolorem sint itaque, officiis nisi accusantium!
            '],
            ['rating' => 5,
            'comment' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. At tempora autem qui dicta ex itaque natus quidem quasi, iste repellat alias doloribus aspernatur? Esse dolorem sint itaque, officiis nisi accusantium!
            '],
        ];

        foreach ($datas as $data) {
            $data['user_id'] = $user->id;
            $data['category_id'] = $category->id;
            $data['status_id'] = $status->id;
            Review::create($data);
        }
    }
}

