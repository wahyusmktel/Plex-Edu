<?php

namespace App\Exports;

use App\Models\Cbt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CbtResultsExport implements FromCollection, WithHeadings, WithStyles
{
    protected $cbt;

    public function __construct(Cbt $cbt)
    {
        $this->cbt = $cbt;
    }

    public function collection()
    {
        $rows = [];
        $rank = 1;

        $sessions = $this->cbt->sessions->sortByDesc('skor');

        foreach ($sessions as $session) {
            $rows[] = [
                $rank++,
                $session->siswa->nama_lengkap ?? 'N/A',
                $session->siswa->nis ?? 'N/A',
                $session->siswa->kelas->nama ?? 'N/A',
                $session->start_time?->format('H:i:s'),
                $session->end_time?->format('H:i:s'),
                $session->skor,
                $this->cbt->skor_maksimal,
                round(($session->skor / $this->cbt->skor_maksimal) * 100, 1) . '%',
                $session->status == 'completed' ? 'Selesai' : 'Berlangsung'
            ];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NIS',
            'Kelas',
            'Jam Mulai',
            'Jam Selesai',
            'Skor',
            'Skor Maks',
            'Persentase',
            'Status'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
