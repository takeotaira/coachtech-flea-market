<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            [
                'email' => 'seller@example.com',
            ],
            [
                'name' => '出品ユーザー',
                'password' => Hash::make('password'),
            ]
        );
    }
}