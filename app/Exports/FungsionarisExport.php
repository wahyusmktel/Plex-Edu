<?php

namespace App\Exports;

use App\Models\Fungsionaris;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class FungsionarisExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithColumnFormatting, ShouldAutoSize
{
    protected $jabatan;

    public function __construct($jabatan = null)
    {
        $this->jabatan = $jabatan;
    }

    public function collection()
    {
        $query = Fungsionaris::with('user');
        
        if ($this->jabatan) {
            $query->where('jabatan', $this->jabatan);
        }
        
        return $query->orderBy('nama', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NIP',
            'NIK',
            'Jabatan',
            'Posisi',
            'Status',
            'Username',
            'Email',
            'Password Default',
            'No HP',
        ];
    }

    public function map($fungsionaris): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $fungsionaris->nama,
            $fungsionaris->nip,
            $fungsionaris->nik,
            ucfirst($fungsionaris->jabatan),
            $fungsionaris->posisi,
            ucfirst($fungsionaris->status),
            $fungsionaris->user->username ?? '-',
            $fungsionaris->user->email ?? '-',
            $fungsionaris->user_id ? 'literasia' : '-',
            $fungsionaris->no_hp ?? '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 20,
            'D' => 20,
            'E' => 12,
            'F' => 25,
            'G' => 12,
            'H' => 15,
            'I' => 30,
            'J' => 18,
            'K' => 15,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        return [
            // Header style
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D90D8B'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // All cells border
            'A1:K' . $lastRow => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ],
            // Center specific columns
            'A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'E' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'G' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'H' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'J' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
        ];
    }
}
