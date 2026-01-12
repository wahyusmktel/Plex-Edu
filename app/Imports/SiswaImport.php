<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            // Find kelas by nama
            $kelas = Kelas::where('nama', $row['kelas'])->first();
            
            $user = User::create([
                'name'     => $row['nama_lengkap'],
                'username' => $row['username'],
                'password' => Hash::make($row['password']),
                'role'     => 'siswa',
                'email'    => $row['username'] . '@siswa.literasia.com',
            ]);

            return new Siswa([
                'user_id'       => $user->id,
                'kelas_id'      => $kelas ? $kelas->id : null,
                'nis'           => $row['nis'],
                'nisn'          => $row['nisn'],
                'nama_lengkap'  => $row['nama_lengkap'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'tempat_lahir'  => $row['tempat_lahir'] ?? null,
                'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
                'alamat'        => $row['alamat'] ?? null,
                'nama_ayah'     => $row['nama_ayah'] ?? null,
                'nama_ibu'      => $row['nama_ibu'] ?? null,
                'no_hp'         => $row['no_hp'] ?? null,
                'no_hp_ortu'    => $row['no_hp_ortu'] ?? null,
                'sekolah_asal'  => $row['sekolah_asal'] ?? null,
            ]);
        });
    }
}
