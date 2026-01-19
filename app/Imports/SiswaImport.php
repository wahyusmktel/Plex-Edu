<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find kelas by nama
        $kelas = Kelas::where('nama', $row['kelas'])->first();

        // Only create siswa data, user account will be generated separately
        return new Siswa([
            'user_id'       => null, // User will be generated separately
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
    }

    public function rules(): array
    {
        return [
            'nis'           => 'required|unique:siswas,nis',
            'nisn'          => 'required|unique:siswas,nisn',
            'nama_lengkap'  => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas'         => 'required|exists:kelas,nama',
        ];
    }
}
