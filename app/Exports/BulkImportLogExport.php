<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BulkImportLogExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->data as $item) {
            $status = strtoupper($item['status'] ?? '-');
            $message = $item['message'] ?? '-';
            
            if (!empty($item['errors'])) {
                foreach ($item['errors'] as $error) {
                    $rows[] = [
                        $item['school_name'] ?? '-',
                        $item['npsn'] ?? '-',
                        $item['filename'] ?? '-',
                        $status,
                        "Baris " . ($error['row'] ?? '-') . ": " . ($error['student'] ?? '-') . " - " . ($error['error'] ?? '-')
                    ];
                }
            } else {
                $rows[] = [
                    $item['school_name'] ?? '-',
                    $item['npsn'] ?? '-',
                    $item['filename'] ?? '-',
                    $status,
                    $message
                ];
            }
        }
        return $rows;
    }

    public function headings(): array
    {
        return [
            'Nama Sekolah',
            'NPSN',
            'Nama File',
            'Status',
            'Keterangan / Detail Error'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
