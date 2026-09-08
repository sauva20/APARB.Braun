<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Manager Keselamatan',
            'email' => 'head@braun.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'Head of EHSS, SM, OE & LPMO',
            'pin' => '123456',
        ]);

        \App\Models\User::create([
            'name' => 'Andi Rahman',
            'email' => 'andi@braun.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'EHSS',
            'pin' => '654321',
        ]);
    }
}
