<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'fathy',
            'email' => 'fathy@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        User::create([
            'name' => 'ahmed',
            'email' => 'ahmed@gmail.com',
            'password' => Hash::make('12345678')
        ]);
    }
}
