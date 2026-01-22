<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                '1', // A - No
                'Ahmad Syahputra', // B - Nama
                '232410001', // C - NIPD
                'L', // D - JK
                '0012345678', // E - NISN
                'Jakarta', // F - Tempat Lahir
                '2008-01-01', // G - Tanggal Lahir
                '3201230101080001', // H - NIK
                'Islam', // I - Agama
                'Jl. Contoh No. 123', // J - Alamat
                '001', // K - RT
                '002', // L - RW
                'Dusun I', // M - Dusun
                'Kelurahan Cipete', // N - Kelurahan
                'Kecamatan Cilandak', // O - Kecamatan
                '12410', // P - Kode Pos
                'Bersama orang tua', // Q - Jenis Tinggal
                'Sepeda motor', // R - Alat Transportasi
                '0217890XXX', // S - Telepon
                '08123456789', // T - HP
                'ahmad@gmail.com', // U - E-Mail
                'SKHUN123', // V - SKHUN
                'Tidak', // W - Penerima KPS
                '', // X - No. KPS
                'Bapak Ahmad', // Y - Nama Ayah
                '1975', // Z - Tahun Lahir Ayah
                'SMA / sederajat', // AA - Pendidikan Ayah
                'Wiraswasta', // AB - Pekerjaan Ayah
                'Rp. 2,000,000 - Rp. 4,999,999', // AC - Penghasilan Ayah
                '320123XXXXXXXXXX', // AD - NIK Ayah
                'Ibu Ahmad', // AE - Nama Ibu
                '1980', // AF - Tahun Lahir Ibu
                'SMA / sederajat', // AG - Pendidikan Ibu
                'Karyawan Swasta', // AH - Pekerjaan Ibu
                'Rp. 1,000,000 - Rp. 1,999,999', // AI - Penghasilan Ibu
                '320123XXXXXXXXXX', // AJ - NIK Ibu
                '', // AK - Nama Wali
                '', // AL - Tahun Lahir Wali
                '', // AM - Pendidikan Wali
                '', // AN - Pekerjaan Wali
                '', // AO - Penghasilan Wali
                '', // AP - NIK Wali
                'Kelas 1', // AQ - Rombel Saat Ini
                '', // AR - No Peserta Ujian Nasional
                '', // AS - No Seri Ijazah
                'Tidak', // AT - Penerima KIP
                '', // AU - Nomor KIP
                '', // AV - Nama di KIP
                '', // AW - Nomor KKS
                'AKTXXXXX', // AX - No Registrasi Akta Lahir
                'BRI', // AY - Bank
                '0123456789', // AZ - Nomor Rekening Bank
                'Ahmad Syahputra', // BA - Rekening Atas Nama
                'Tidak', // BB - Layak PIP
                '', // BC - Alasan Layak PIP
                'Tidak ada', // BD - Kebutuhan Khusus
                'SMPN 1 Jakarta', // BE - Sekolah Asal
                '1', // BF - Anak ke-berapa
                '', // BG - Lintang
                '', // BH - Bujur
                '320123XXXXXXXXXX', // BI - No KK
                '50', // BJ - Berat Badan
                '160', // BK - Tinggi Badan
                '54', // BL - Lingkar Kepala
                '2', // BM - Jml. Saudara Kandung
                '2', // BN - Jarak Rumah ke Sekolah (KM)
            ]
        ];
    }

    public function headings(): array
    {
        return [
            ['No', 'Nama', 'NIPD', 'JK', 'NISN', 'Tempat Lahir', 'Tanggal Lahir', 'NIK', 'Agama', 'Alamat', 'RT', 'RW', 'Dusun', 'Kelurahan', 'Kecamatan', 'Kode Pos', 'Jenis Tinggal', 'Alat Transportasi', 'Telepon', 'HP', 'E-Mail', 'SKHUN', 'Penerima KPS', 'No. KPS', 'Nama Ayah', 'Tahun Lahir Ayah', 'Pendidikan Ayah', 'Pekerjaan Ayah', 'Penghasilan Ayah', 'NIK Ayah', 'Nama Ibu', 'Tahun Lahir Ibu', 'Pendidikan Ibu', 'Pekerjaan Ibu', 'Penghasilan Ibu', 'NIK Ibu', 'Nama Wali', 'Tahun Lahir Wali', 'Pendidikan Wali', 'Pekerjaan Wali', 'Penghasilan Wali', 'NIK Wali', 'Rombel Saat Ini', 'No Peserta Ujian Nasional', 'No Seri Ijazah', 'Penerima KIP', 'Nomor KIP', 'Nama di KIP', 'Nomor KKS', 'No Registrasi Akta Lahir', 'Bank', 'Nomor Rekening Bank', 'Rekening Atas Nama', 'Layak PIP', 'Alasan Layak PIP', 'Kebutuhan Khusus', 'Sekolah Asal', 'Anak ke-berapa', 'Lintang', 'Bujur', 'No KK', 'Berat Badan', 'Tinggi Badan', 'Lingkar Kepala', 'Jml. Saudara Kandung', 'Jarak Rumah ke Sekolah (KM)']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
