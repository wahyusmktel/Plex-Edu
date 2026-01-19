<?php

namespace App\Imports;

use App\Models\Fungsionaris;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class FungsionarisImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row)
    {
        $schoolId = auth()->user()->school_id;

        if (!$schoolId) {
            throw new \Exception('Akun Anda tidak terasosiasi dengan sekolah. Import dibatalkan.');
        }

        // Helper to find key by keyword because slugging can vary
        $findKey = function($keyword) use ($row) {
            return collect(array_keys($row))->first(fn($k) => str_contains(strtolower($k), $keyword));
        };

        $namaKey = $findKey('nama') ?? 'nama';
        $jabatanKey = $findKey('jabatan') ?? 'jabatan';
        $statusKey = $findKey('status') ?? 'status';
        $tglLahirKey = $findKey('tanggal_lahir') ?? 'tanggal_lahir';
        $jkKey = $findKey('jenis_kelamin') ?? 'jenis_kelamin';

        $nama = $row[$namaKey] ?? null;
        $nip = $row['nip'] ?? null;
        $nik = $row['nik'] ?? null;
        $posisi = $row['posisi'] ?? null;
        
        $jabatanRaw = strtolower($row[$jabatanKey] ?? 'guru');
        $jabatan = str_contains($jabatanRaw, 'pegawai') ? 'pegawai' : 'guru';
        
        $statusRaw = strtolower($row[$statusKey] ?? 'aktif');
        $status = str_contains($statusRaw, 'non') ? 'nonaktif' : 'aktif';
        
        $no_hp = $row['no_hp'] ?? null;
        $alamat = $row['alamat'] ?? null;
        $tempat_lahir = $row['tempat_lahir'] ?? null;
        $tanggal_lahir = $row[$tglLahirKey] ?? null;
        
        $jkRaw = strtoupper($row[$jkKey] ?? '');
        $jenis_kelamin = str_contains($jkRaw, 'P') ? 'P' : 'L';
        
        $pendidikan = $row['pendidikan_terakhir'] ?? null;

        // Only create fungsionaris data, user account will be generated separately
        return new Fungsionaris([
            'school_id'           => $schoolId,
            'user_id'             => null, // User will be generated separately
            'nama'                => $nama,
            'nip'                 => $nip,
            'nik'                 => $nik,
            'posisi'              => $posisi,
            'jabatan'             => $jabatan,
            'status'              => $status,
            'no_hp'               => $no_hp,
            'alamat'              => $alamat,
            'tempat_lahir'        => $tempat_lahir,
            'tanggal_lahir'       => $tanggal_lahir,
            'jenis_kelamin'       => $jenis_kelamin,
            'pendidikan_terakhir' => $pendidikan,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nip' => 'required|unique:fungsionaris,nip',
            '*.nik' => 'required|unique:fungsionaris,nik',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nip.unique' => 'NIP :input sudah terdaftar.',
            '*.nik.unique' => 'NIK :input sudah terdaftar.',
        ];
    }
}

