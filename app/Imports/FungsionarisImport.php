<?php

namespace App\Imports;

use App\Models\Fungsionaris;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FungsionarisImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            $user = User::create([
                'name'     => $row['nama'],
                'username' => $row['username'],
                'password' => Hash::make($row['password']),
                'role'     => $row['jabatan'] === 'guru' ? 'guru' : 'pegawai',
                'email'    => $row['username'] . '@literasia.com',
            ]);

            return new Fungsionaris([
                'user_id'             => $user->id,
                'nama'                => $row['nama'],
                'nip'                 => $row['nip'],
                'nik'                 => $row['nik'],
                'posisi'              => $row['posisi'],
                'jabatan'             => $row['jabatan'],
                'status'              => $row['status'],
                'no_hp'               => $row['no_hp'],
                'alamat'              => $row['alamat'],
                'tempat_lahir'        => $row['tempat_lahir'],
                'tanggal_lahir'       => $row['tanggal_lahir'],
                'jenis_kelamin'       => $row['jenis_kelamin'],
                'pendidikan_terakhir' => $row['pendidikan_terakhir'],
            ]);
        });
    }
}
