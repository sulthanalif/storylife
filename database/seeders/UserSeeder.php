<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Sulthan Alif Hayatyo', 'email' => 'sulthan@gmail.com', 'password' => 'password', 'api_token' => 'TFhhVVZtMGtSNlBlTENESjhzVG1KRXY2WVg2Ymo4V1pjbFdkWTkzUA=='],
            ['name' => 'Sutio Mudiarno', 'email' => 'sutio@gmail.com', 'password' => 'password', 'api_token' => 'TFhhVVZtMGtSNlBlTENESjhzVG1KRXY2WVg2Ymo4V1pjbFdkWTkzUA=='],
            ['name' => 'Muh Hilman Sholehudin', 'email' => 'hilman@gmail.com', 'password' => 'password', 'api_token' => 'TFhhVVZtMGtSNlBlTENESjhzVG1KRXY2WVg2Ymo4V1pjbFdkWTkzUA=='],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
