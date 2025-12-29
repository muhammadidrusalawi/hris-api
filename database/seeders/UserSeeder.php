<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => Str::uuid(),
            'name' => 'Admin HRIS',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => 'Muhammad Iqbal',
            'email' => 'muhammad.iqbal@gmail.com',
            'role' => 'employee',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => 'Muhammad Idrus',
            'email' => 'muhammad.idrus@gmail.com',
            'role' => 'employee',
            'password' => Hash::make('12345678'),
        ]);
    }
}
