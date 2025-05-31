<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin@123'),
                'role' => 'admin',
            ],
            [
                'name' => 'user1',
                'email' => 'ddumy209@gmail.com',
                'password' => Hash::make('user1@123'),
                'role' => 'user',
            ],
            [
                'name' => 'user2',
                'email' => 'user2@gmail.com',
                'password' => Hash::make('user2@123'),
                'role' => 'user',
            ],
            [
                'name' => 'user3',
                'email' => 'user3@gmail.com',
                'password' => Hash::make('user3@123'),
                'role' => 'user',
            ],
        ];


        User::insert($data);
    }
}
