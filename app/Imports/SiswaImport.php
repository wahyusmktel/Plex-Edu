<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Auth;

class SiswaImport implements ToModel, WithStartRow
{
    protected $schoolId;

    protected $processedNis = [];
    protected $processedNisn = [];

    public function __construct($schoolId = null)
    {
        $this->schoolId = $schoolId ?? (Auth::check() ? Auth::user()->school_id : null);
    }

    public function model(array $row)
    {
        try {
            // row index 0 corresponds to Column A
            // We start from Column B (index 1) to Column BN (index 65)
            
            $col = function($letter) use ($row) {
                $index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($letter) - 1;
                $val = $row[$index] ?? null;
                return is_string($val) ? trim($val) : $val;
            };

            $namaLengkap = $col('B');
            if (!$namaLengkap) return null;

            $nisn = $col('E');
            $nis = $col('C');

            // Handle duplicate within the same import session
            if (($nisn && in_array($nisn, $this->processedNisn)) || ($nis && in_array($nis, $this->processedNis))) {
                // Skip if we already processed this student in this session (updates are handled by the upsert logic if it's the first time in session)
                // Actually, if it's in processed list, it means we already created/updated it in this request.
                // To support multiple rows for same student in one file (not recommended but happens), 
                // we should update AGAIN or just skip. Usually skipping is safer or just let the update happen.
                // But ToModel returns an instance to be saved. If we return an existing model, it might try to insert.
            }

            $namaKelas = $col('AQ');
            $kelasId = null;

            if ($namaKelas && $this->schoolId) {
                $kelas = Kelas::where('nama', $namaKelas)
                    ->where('school_id', $this->schoolId)
                    ->first();

                if (!$kelas) {
                    // Auto create class if not exists
                    $kelas = Kelas::create([
                        'school_id' => $this->schoolId,
                        'nama' => $namaKelas,
                        'tingkat' => $this->extractTingkat($namaKelas),
                    ]);
                }
                $kelasId = $kelas->id;
            }

            $rawLintang = $col('BG');
            $rawBujur = $col('BH');
            
            // Smart Mapping: Dapodik often swaps Lintang (Lat) and Bujur (Lng)
            // Latitude is max 90, Longitude is up to 180.
            // If BG > 90 or < -90, it's almost certainly Longitude.
            $lintang = $rawLintang;
            $bujur = $rawBujur;

            if (is_numeric($rawLintang) && is_numeric($rawBujur)) {
                if (abs($rawLintang) > 90 && abs($rawBujur) <= 90) {
                    // Looks like they are swapped!
                    $lintang = $rawBujur;
                    $bujur = $rawLintang;
                    // \Log::info("Swapping Lat/Lng for " . $namaLengkap . ": Lat=$rawBujur, Lng=$rawLintang");
                }
            }

            $computedNis = $nis ?: ($nisn ?: $namaLengkap);

            $siswaData = [
                'school_id'           => $this->schoolId,
                'nama_lengkap'        => $namaLengkap,
                'nipd'                => $nis,
                'jenis_kelamin'       => $col('D'),
                'nisn'                => $nisn,
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
                'lintang'             => $lintang,
                'bujur'               => $bujur,
                'no_kk'               => $col('BI'),
                'berat_badan'         => $col('BJ'),
                'tinggi_badan'        => $col('BK'),
                'lingkar_kepala'      => $col('BL'),
                'jml_saudara_kandung' => $col('BM'),
                'jarak_rumah_km'      => $col('BN'),
                'nis'                 => $computedNis,
            ];

            // Super Upsert: Find by NISN OR by NIS (Scoped to school now)
            $existingSiswa = null;

            if ($nisn) {
                $existingSiswa = Siswa::withoutGlobalScopes()->where('school_id', $this->schoolId)->where('nisn', $nisn)->first();
            }

            if (!$existingSiswa && $computedNis) {
                $existingSiswa = Siswa::withoutGlobalScopes()->where('school_id', $this->schoolId)->where('nis', $computedNis)->first();
            }

            if ($existingSiswa) {
                // If found, update its data
                $existingSiswa->update($siswaData);
                
                // Track as processed in this session
                if ($nisn) $this->processedNisn[] = $nisn;
                if ($computedNis) $this->processedNis[] = $computedNis;
                
                return null;
            }

            // Track as processed in this session before returning new model
            if ($nisn) $this->processedNisn[] = $nisn;
            if ($computedNis) $this->processedNis[] = $computedNis;

            return new Siswa($siswaData);
        } catch (\Exception $e) {
            \Log::error("SiswaImport Error at " . ($namaLengkap ?? 'Unknown') . ": " . $e->getMessage());
            throw $e;
        }
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
