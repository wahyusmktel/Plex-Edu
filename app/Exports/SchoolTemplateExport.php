<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SchoolTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            [
                'SMA Negeri 1 Jakarta',
                '12345678',
                'sma_smk',
                'Negeri',
                'DKI Jakarta',
                'Jakarta Pusat',
                'Gambir',
                'Gambir',
                'Jl. Merdeka No. 10',
                '-6.175392',
                '106.827153',
            ],
            [
                'SMK Swasta Harapan Bangsa',
                '87654321',
                'sma_smk',
                'Swasta',
                'Jawa Barat',
                'Bandung',
                'Coblong',
                'Dago',
                'Jl. Ir. H. Juanda No. 5',
                '-6.891480',
                '107.610659',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NAMA SEKOLAH',
            'NPSN',
            'JENJANG',
            'STATUS SEKOLAH',
            'PROVINSI',
            'KABUPATEN KOTA',
            'KECAMATAN',
            'DESA KELURAHAN',
            'ALAMAT',
            'LINTANG',
            'BUJUR',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D90D8B'],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 36,
            'B' => 16,
            'C' => 12,
            'D' => 18,
            'E' => 20,
            'F' => 24,
            'G' => 20,
            'H' => 22,
            'I' => 40,
            'J' => 18,
            'K' => 18,
        ];
    }
}
