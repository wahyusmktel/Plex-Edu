<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BankSoalTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            [
                'pilihan_ganda',
                'Benda yang dapat menghantarkan listrik dengan baik disebut...',
                5,
                'Konduktor',
                'Isolator',
                'Resistor',
                'Induktor',
                'Semikonduktor',
                'A',
            ],
            [
                'essay',
                'Jelaskan perbedaan antara konduktor dan isolator.',
                10,
                '',
                '',
                '',
                '',
                '',
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'JENIS SOAL',
            'PERTANYAAN',
            'POIN',
            'OPSI A',
            'OPSI B',
            'OPSI C',
            'OPSI D',
            'OPSI E',
            'JAWABAN',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F2937'],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 16,
            'B' => 60,
            'C' => 8,
            'D' => 24,
            'E' => 24,
            'F' => 24,
            'G' => 24,
            'H' => 24,
            'I' => 12,
        ];
    }
}
