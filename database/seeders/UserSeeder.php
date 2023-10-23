<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Sulthan Alif Hayatyo', 'email' => 'sulthan@gmail.com', 'password' => Hash::make('password')],
            ['name' => 'Sutio Mudiarno', 'email' => 'sutio@gmail.com', 'password' => Hash::make('password')],
            ['name' => 'Muh Hilman Sholehudin', 'email' => 'hilman@gmail.com', 'password' => Hash::make('password')],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
