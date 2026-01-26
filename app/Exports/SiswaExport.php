<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'NIS',
            'NISN',
            'Kelas',
            'Jenis Kelamin',
            'Username',
            'Email',
            'Password Default',
        ];
    }

    /**
    * @param Siswa $siswa
    */
    public function map($siswa): array
    {
        return [
            $siswa->nama_lengkap,
            $siswa->nis,
            $siswa->nisn,
            $siswa->kelas ? $siswa->kelas->nama_kelas : '-',
            $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
            $siswa->user ? $siswa->user->username : $siswa->nisn,
            $siswa->user ? $siswa->user->email : $siswa->nisn . '@siswa.literasia.org',
            $siswa->nisn, // Password default is NISN
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
