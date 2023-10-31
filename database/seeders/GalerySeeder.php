<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GalerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::where('name', 'Hunting')->first();
        $imagePath = base_path('public/upload/service.jpg');

    	$datas = [
            [
    	    'tittle' => 'lorem',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate itaque tempore officia sunt nesciunt consequuntur, reiciendis quisquam hic quas excepturi, aliquid rem sit magnam fugiat quo pariatur laborum, doloribus in?'
            ],
            [
    	    'tittle' => 'lorem',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate itaque tempore officia sunt nesciunt consequuntur, reiciendis quisquam hic quas excepturi, aliquid rem sit magnam fugiat quo pariatur laborum, doloribus in?'
            ],
            [
    	    'tittle' => 'lorem',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate itaque tempore officia sunt nesciunt consequuntur, reiciendis quisquam hic quas excepturi, aliquid rem sit magnam fugiat quo pariatur laborum, doloribus in?'
            ],
    	];

        foreach ($datas as $data) {
            $data['category_id'] = $category->id;
            $data['image'] = $imagePath;
            Gallery::create($data);
        }
    }
}
