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
                'Negeri',
                'DKI Jakarta',
                'Jakarta Pusat',
                'Gambir',
                'Gambir',
                'Jl. Merdeka No. 10',
            ],
            [
                'SMK Swasta Harapan Bangsa',
                '87654321',
                'Swasta',
                'Jawa Barat',
                'Bandung',
                'Coblong',
                'Dago',
                'Jl. Ir. H. Juanda No. 5',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NAMA SEKOLAH',
            'NPSN',
            'STATUS SEKOLAH',
            'PROVINSI',
            'KABUPATEN KOTA',
            'KECAMATAN',
            'DESA KELURAHAN',
            'ALAMAT',
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
            'C' => 18,
            'D' => 20,
            'E' => 24,
            'F' => 20,
            'G' => 22,
            'H' => 40,
        ];
    }
}
