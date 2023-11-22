<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Order;
use App\Models\StatusOrder;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::where('name', 'Hunting')->first();
        $user = User::where('email', 'sutio@gmail.com')->first();
        $status1 = StatusOrder::where('name', 'Success')->first();
        $status2 = StatusOrder::where('name', 'Pending')->first();

        $datas = [
            ['status_id' => $status1->id, 'booked_at' => Carbon::now(), 'note' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis dicta, delectus error aperiam suscipit maiores eos omnis corporis cumque, maxime beatae modi id ullam quas, nesciunt harum perspiciatis? Eos, corporis!', 'price'=> 500000],
            ['status_id' => $status1->id, 'booked_at' => Carbon::now(), 'note' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis dicta, delectus error aperiam suscipit maiores eos omnis corporis cumque, maxime beatae modi id ullam quas, nesciunt harum perspiciatis? Eos, corporis!', 'price'=> 500000],
            ['status_id' => $status1->id, 'booked_at' => Carbon::now(), 'note' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis dicta, delectus error aperiam suscipit maiores eos omnis corporis cumque, maxime beatae modi id ullam quas, nesciunt harum perspiciatis? Eos, corporis!', 'price'=> 500000],
            ['status_id' => $status1->id, 'booked_at' => Carbon::now(), 'note' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis dicta, delectus error aperiam suscipit maiores eos omnis corporis cumque, maxime beatae modi id ullam quas, nesciunt harum perspiciatis? Eos, corporis!', 'price'=> 500000],
            ['status_id' => $status2->id, 'booked_at' => Carbon::now(), 'note' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis dicta, delectus error aperiam suscipit maiores eos omnis corporis cumque, maxime beatae modi id ullam quas, nesciunt harum perspiciatis? Eos, corporis!', 'price'=> 500000],
            ['status_id' => $status2->id, 'booked_at' => Carbon::now(), 'note' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis dicta, delectus error aperiam suscipit maiores eos omnis corporis cumque, maxime beatae modi id ullam quas, nesciunt harum perspiciatis? Eos, corporis!', 'price'=> 500000],
        ];

        foreach ($datas as $data) {
            $data['user_id'] = $user->id;
            $data['category_id'] = $category->id;
            Order::create($data);
        }
    }
}
