<?php

namespace App\Exports;

use App\Models\PelanggaranPegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PelanggaranPegawaiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PelanggaranPegawai::with(['fungsionaris', 'masterPelanggaran'])->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Pegawai',
            'Jabatan',
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
            $row->fungsionaris->nama,
            $row->fungsionaris->jabatan,
            $row->masterPelanggaran->nama,
            $row->masterPelanggaran->poin,
            $row->deskripsi,
            $row->tindak_lanjut,
        ];
    }
}
