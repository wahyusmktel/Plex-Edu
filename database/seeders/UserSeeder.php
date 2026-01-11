<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator Literasia',
            'email' => 'admin@literasia.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Guru
        User::create([
            'name' => 'Guru Literasia',
            'email' => 'guru@literasia.com',
            'username' => 'guru',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        // Siswa
        User::create([
            'name' => 'Siswa Literasia',
            'email' => 'siswa@literasia.com',
            'username' => 'siswa',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
    }
}
