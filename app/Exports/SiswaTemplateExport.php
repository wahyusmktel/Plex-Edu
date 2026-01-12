<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'Ahmad Syahputra',
                '232410001',
                '0012345678',
                'ahmad_siswa',
                'password123',
                'L',
                'X-RPL-1',
                'Jakarta',
                '2008-01-01',
                'Jl. Contoh No. 123',
                'Bapak Ahmad',
                'Ibu Ahmad',
                '08123456789',
                '08123456780',
                'SMPN 1 Jakarta'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'nama_lengkap',
            'nis',
            'nisn',
            'username',
            'password',
            'jenis_kelamin',
            'kelas', // Matches by name in SiswaImport
            'tempat_lahir',
            'tanggal_lahir',
            'alamat',
            'nama_ayah',
            'nama_ibu',
            'no_hp',
            'no_hp_ortu',
            'sekolah_asal'
        ];
    }
}
