<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
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

        // Kelas Default
        $kelas = Kelas::create([
            'nama' => 'X MIPA 1',
            'tingkat' => '10',
            'kapasitas' => 36
        ]);

        // Siswa
        $userSiswa = User::create([
            'name' => 'Siswa Literasia',
            'email' => 'siswa@literasia.com',
            'username' => 'siswa',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        Siswa::create([
            'user_id' => $userSiswa->id,
            'kelas_id' => $kelas->id,
            'nis' => '12345',
            'nisn' => '0012345678',
            'nama_lengkap' => 'Siswa Literasia',
            'jenis_kelamin' => 'L',
        ]);
    }
}
