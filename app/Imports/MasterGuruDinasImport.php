<?php

namespace App\Imports;

use App\Models\MasterGuruDinas;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class MasterGuruDinasImport implements ToModel, WithStartRow, WithCalculatedFormulas
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 6;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Column mapping based on Excel Row 5/6
        // A: No. (index 0)
        // B: Nama (index 1)
        // C: NIK (index 2)
        // D: NUPTK (index 3)
        // E: NIP (index 4)
        // F: L/P (index 5)
        // G: Tempat Lahir (index 6)
        // H: Tanggal Lahir (index 7)
        // I: Status Tugas (index 8)
        // J: Tempat Tugas (index 9)
        // K: NPSN (index 10)
        // L: Kecamatan (index 11)
        // M: Kabupaten/Kota (index 12)
        // N: Nomor HP (index 13)
        // O: SK CPNS (index 14)
        // P: Tanggal CPNS (index 15)
        // Q: SK Pengangkatan (index 16)
        // R: TMT Pengangkatan (index 17)
        // S: Jenis PTK (index 18)
        // T: Jabatan PTK (index 19)
        // U: Pendidikan (index 20)
        // V: Bidang Studi Pendidikan (index 21)
        // W: Bidang Studi Sertifikasi (index 22)
        // X: Status Kepegawaian (index 23)
        // Y: Pangkat/Gol (index 24)
        // Z: TMT Pangkat (index 25)
        // AA: Masa Kerja Tahun (index 26)
        // AB: Masa Kerja Bulan (index 27)
        // AC: Mata Pelajaran Diajarkan (index 28)
        // AD: Jam Mengajar Perminggu (index 29)
        // AE: Jabatan Kepsek (index 30)

        // Helper to parse dates
        $parseDate = function($val) {
            if (empty($val) || $val == '1900-01-01' || $val == '0000-00-00') return null;
            try {
                // If numeric (Excel date format), use Carbon::instance
                if (is_numeric($val)) {
                    return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val);
                }
                return Carbon::parse($val);
            } catch (\Exception $e) {
                return null;
            }
        };

        $nik = trim(str_replace("\u{200c}", '', $row[2] ?? ''));
        $nip = trim(str_replace("\u{200c}", '', $row[4] ?? ''));
        $nuptk = trim(str_replace("\u{200c}", '', $row[3] ?? ''));

        // Avoid empty rows or non-data rows
        if (empty($row[1])) {
            return null;
        }

        return new MasterGuruDinas([
            'nama'                     => $row[1] ?? null,
            'nik'                      => $nik ?: null,
            'nuptk'                    => $nuptk ?: null,
            'nip'                      => $nip ?: null,
            'jenis_kelamin'            => $row[5] ?? null,
            'tempat_lahir'             => $row[6] ?? null,
            'tanggal_lahir'            => $parseDate($row[7] ?? null),
            'status_tugas'             => $row[8] ?? null,
            'tempat_tugas'             => $row[9] ?? null,
            'npsn'                     => $row[10] ?? null,
            'kecamatan'                => $row[11] ?? null,
            'kabupaten_kota'           => $row[12] ?? null,
            'no_hp'                    => $row[13] ?? null,
            'sk_cpns'                  => $row[14] ?? null,
            'tanggal_cpns'             => $parseDate($row[15] ?? null),
            'sk_pengangkatan'          => $row[16] ?? null,
            'tmt_pengangkatan'         => $parseDate($row[17] ?? null),
            'jenis_ptk'                => $row[18] ?? null,
            'jabatan_ptk'              => $row[19] ?? null,
            'pendidikan'               => $row[20] ?? null,
            'bidang_studi_pendidikan'  => $row[21] ?? null,
            'bidang_studi_sertifikasi' => $row[22] ?? null,
            'status_kepegawaian'       => $row[23] ?? null,
            'pangkat_golongan'         => $row[24] ?? null,
            'tmt_pangkat'              => $parseDate($row[25] ?? null),
            'masa_kerja_tahun'         => intval($row[26] ?? 0),
            'masa_kerja_bulan'         => intval($row[27] ?? 0),
            'mata_pelajaran_diajarkan' => $row[28] ?? null,
            'jam_mengajar_perminggu'   => intval($row[29] ?? 0),
            'jabatan_kepsek'           => $row[30] ?? null,
        ]);
    }
}
