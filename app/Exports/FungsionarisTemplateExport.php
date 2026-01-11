<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FungsionarisTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'Budi Santoso',
                '198501012010011001',
                '3201010101010001',
                'Guru Matematika',
                'guru',
                'budi_guru',
                'password123',
                'aktif',
                '08123456789',
                'Jl. Pendidikan No. 123',
                'Jakarta',
                '1985-01-01',
                'L',
                'S1 Pendidikan Matematika'
            ],
            [
                'Siti Aminah',
                '199005052015022002',
                '3201010505900002',
                'Staf Administrasi',
                'pegawai',
                'siti_staf',
                'password123',
                'aktif',
                '08987654321',
                'Jl. Merdeka No. 45',
                'Bandung',
                '1990-05-05',
                'P',
                'D3 Administrasi Perkantoran'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'nama',
            'nip',
            'nik',
            'posisi',
            'jabatan',
            'username',
            'password',
            'status',
            'no_hp',
            'alamat',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'pendidikan_terakhir'
        ];
    }
}
