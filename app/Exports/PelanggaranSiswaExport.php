<?php

namespace App\Exports;

use App\Models\PelanggaranSiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PelanggaranSiswaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PelanggaranSiswa::with(['siswa.kelas', 'masterPelanggaran'])->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Siswa',
            'Kelas',
            'Pelanggaran',
            'Poin',
            'Deskripsi',
            'Tindak Lanjut',
        ];
    }

    public function map($row): array
    {
        return [
            $row->tanggal,
            $row->siswa->nama_lengkap,
            $row->siswa->kelas->nama ?? '-',
            $row->masterPelanggaran->nama,
            $row->masterPelanggaran->poin,
            $row->deskripsi,
            $row->tindak_lanjut,
        ];
    }
}
