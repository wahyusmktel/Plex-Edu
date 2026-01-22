<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Auth;

class SiswaImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // row index 0 corresponds to Column A
        // We start from Column B (index 1) to Column BN (index 65)
        
        $col = function($letter) use ($row) {
            $index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($letter) - 1;
            return $row[$index] ?? null;
        };

        $namaLengkap = $col('B');
        if (!$namaLengkap) return null;

        $namaKelas = $col('AQ');
        $kelasId = null;

        if ($namaKelas) {
            $schoolId = Auth::user()->school_id;
            $kelas = Kelas::where('nama', $namaKelas)
                ->where('school_id', $schoolId)
                ->first();

            if (!$kelas) {
                // Auto create class if not exists
                $kelas = Kelas::create([
                    'school_id' => $schoolId,
                    'nama' => $namaKelas,
                    'tingkat' => $this->extractTingkat($namaKelas),
                ]);
            }
            $kelasId = $kelas->id;
        }

        return new Siswa([
            'nama_lengkap'        => $namaLengkap,
            'nipd'                => $col('C'),
            'jenis_kelamin'       => $col('D'),
            'nisn'                => $col('E'),
            'tempat_lahir'        => $col('F'),
            'tanggal_lahir'       => $this->formatDate($col('G')),
            'nik'                 => $col('H'),
            'agama'               => $col('I'),
            'alamat'              => $col('J'),
            'rt'                  => $col('K'),
            'rw'                  => $col('L'),
            'dusun'               => $col('M'),
            'kelurahan'           => $col('N'),
            'kecamatan'           => $col('O'),
            'kode_pos'            => $col('P'),
            'jenis_tinggal'       => $col('Q'),
            'alat_transportasi'   => $col('R'),
            'telepon'             => $col('S'),
            'no_hp'               => $col('T'),
            'email'               => $col('U'),
            'skhun'               => $col('V'),
            'penerima_kps'        => $col('W'),
            'no_kps'              => $col('X'),
            'nama_ayah'           => $col('Y'),
            'ayah_tahun_lahir'    => $col('Z'),
            'ayah_pendidikan'     => $col('AA'),
            'ayah_pekerjaan'      => $col('AB'),
            'ayah_penghasilan'    => $col('AC'),
            'ayah_nik'            => $col('AD'),
            'nama_ibu'            => $col('AE'),
            'ibu_tahun_lahir'     => $col('AF'),
            'ibu_pendidikan'      => $col('AG'),
            'ibu_pekerjaan'       => $col('AH'),
            'ibu_penghasilan'     => $col('AI'),
            'ibu_nik'             => $col('AJ'),
            'nama_wali'           => $col('AK'),
            'wali_tahun_lahir'    => $col('AL'),
            'wali_pendidikan'     => $col('AM'),
            'wali_pekerjaan'      => $col('AN'),
            'wali_penghasilan'    => $col('AO'),
            'wali_nik'            => $col('AP'),
            'kelas_id'            => $kelasId,
            'no_peserta_ujian'    => $col('AR'),
            'no_seri_ijazah'      => $col('AS'),
            'penerima_kip'        => $col('AT'),
            'no_kip'              => $col('AU'),
            'nama_di_kip'         => $col('AV'),
            'no_kks'              => $col('AW'),
            'no_akta_lahir'       => $col('AX'),
            'bank'                => $col('AY'),
            'no_rekening_bank'    => $col('AZ'),
            'rekening_atas_nama'  => $col('BA'),
            'layak_pip'           => $col('BB'),
            'alasan_layak_pip'    => $col('BC'),
            'kebutuhan_khusus'    => $col('BD'),
            'sekolah_asal'        => $col('BE'),
            'anak_ke'             => $col('BF'),
            'lintang'             => $col('BG'),
            'bujur'               => $col('BH'),
            'no_kk'               => $col('BI'),
            'berat_badan'         => $col('BJ'),
            'tinggi_badan'        => $col('BK'),
            'lingkar_kepala'      => $col('BL'),
            'jml_saudara_kandung' => $col('BM'),
            'jarak_rumah_km'      => $col('BN'),
            'nis'                 => $col('C') ?? $col('E'), // Fallback NIS to NIPD or NISN
        ]);
    }

    public function startRow(): int
    {
        return 7;
    }

    private function extractTingkat($namaKelas)
    {
        // Simple logic to extract tingkat from "Kelas X" or similar
        if (preg_match('/\d+/', $namaKelas, $matches)) {
            return $matches[0];
        }
        return '1';
    }

    private function formatDate($date)
    {
        if (!$date) return null;
        try {
            if (is_numeric($date)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
            }
            return date('Y-m-d', strtotime($date));
        } catch (\Exception $e) {
            return null;
        }
    }
}
