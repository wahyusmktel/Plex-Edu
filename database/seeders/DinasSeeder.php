<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Dinas Pendidikan',
            'email' => 'dinas@literasia.com',
            'username' => 'dinas',
            'password' => Hash::make('password'),
            'role' => 'dinas',
            'school_id' => null, // central admin doesn't belong to a specific school
        ]);
    }
}
