<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FungsionarisTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            [
                'Budi Santoso, S.Pd.',
                '198501012010011001',
                '3201010101010001',
                'Guru Matematika',
                'guru',
                'budi.santoso',
                'password123',
                'aktif',
                '08123456789',
                'Jl. Pendidikan No. 123, Jakarta Selatan',
                'Jakarta',
                '1985-01-01',
                'L',
                'S1 Pendidikan Matematika'
            ],
            [
                'Siti Aminah, A.Md.',
                '199005052015022002',
                '3201010505900002',
                'Staf Administrasi',
                'pegawai',
                'siti.aminah',
                'password123',
                'aktif',
                '08987654321',
                'Jl. Merdeka No. 45, Bandung',
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
            'NAMA LENGKAP',
            'NIP',
            'NIK',
            'POSISI',
            'JABATAN (guru/pegawai)',
            'USERNAME',
            'PASSWORD',
            'STATUS (aktif/nonaktif)',
            'NO HP',
            'ALAMAT',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR (YYYY-MM-DD)',
            'JENIS KELAMIN (L/P)',
            'PENDIDIKAN TERAKHIR'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D90D8B'] // Literasia Pink
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 25,
            'C' => 25,
            'D' => 25,
            'E' => 15,
            'F' => 20,
            'G' => 15,
            'H' => 15,
            'I' => 20,
            'J' => 40,
            'K' => 20,
            'L' => 25,
            'M' => 15,
            'N' => 30,
        ];
    }
}
