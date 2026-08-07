<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // database/seeders/UserSeeder.php
        User::updateOrCreate(['email' => 'admin.oneheath@gmail.com'], [
            'name'              => 'One Health Admin',
            'email'             => 'admin.oneheath@gmail.com',
            'password'          => Hash::make('admin123'),
            'role'              => 'admin',
            'status'            => 'approved',
            'email_verified_at' => now(),
        ]);
    }
}
