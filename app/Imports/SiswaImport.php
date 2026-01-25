<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SiswaImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure, SkipsOnError, SkipsEmptyRows
{
    use SkipsFailures, SkipsErrors;

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
            
            // Smart fix: Auto-correct coordinates with missing decimal points
            // Example: -53343414 should become -5.3343414, 1052685 should become 105.2685
            $lintang = $this->fixCoordinate($rawLintang, 'latitude');
            $bujur = $this->fixCoordinate($rawBujur, 'longitude');

            // Smart Mapping: Dapodik often swaps Lintang (Lat) and Bujur (Lng)
            // Latitude is max 90, Longitude is up to 180.
            // If lintang > 90 or < -90, it's almost certainly Longitude.
            if (is_numeric($lintang) && is_numeric($bujur) && $lintang != 0 && $bujur != 0) {
                if (abs($lintang) > 90 && abs($bujur) <= 90) {
                    // Looks like they are swapped!
                    $temp = $lintang;
                    $lintang = $bujur;
                    $bujur = $temp;
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

            if ($nisn && $nisn != '-' && $nisn != '?') {
                $existingSiswa = Siswa::withoutGlobalScopes()->where('school_id', $this->schoolId)->where('nisn', $nisn)->first();
            }

            if (!$existingSiswa && $computedNis && $computedNis != '-' && $computedNis != '?') {
                $existingSiswa = Siswa::withoutGlobalScopes()->where('school_id', $this->schoolId)->where('nis', $computedNis)->first();
            }

            if ($existingSiswa) {
                // If found, update its data
                $existingSiswa->update($siswaData);
                
                // Track as processed in this session
                if ($nisn && $nisn != '-' && $nisn != '?') $this->processedNisn[] = $nisn;
                if ($computedNis && $computedNis != '-' && $computedNis != '?') $this->processedNis[] = $computedNis;
                
                return null;
            }

            // Track as processed in this session before returning new model
            if ($nisn && $nisn != '-' && $nisn != '?') $this->processedNisn[] = $nisn;
            if ($computedNis && $computedNis != '-' && $computedNis != '?') $this->processedNis[] = $computedNis;

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

    /**
     * Define when a row is considered empty.
     * A row is considered empty if the key field (Nama Lengkap - column B) is empty.
     * This prevents empty rows from being imported and causing validation errors.
     */
    public function isEmptyWhen(array $row): bool
    {
        // Column B (Nama Lengkap) is at index 1
        $namaLengkap = $row[1] ?? null;
        
        // Consider row empty if Nama Lengkap is null, empty string, or only whitespace
        return empty($namaLengkap) || (is_string($namaLengkap) && trim($namaLengkap) === '');
    }

    private function extractTingkat($namaKelas)
    {
        // Simple logic to extract tingkat from "Kelas X" or similar
        if (preg_match('/\d+/', $namaKelas, $matches)) {
            return $matches[0];
        }
        return '1';
    }

    public function rules(): array
    {
        return [
            // Lintang and Bujur validation removed - handled by fixCoordinate() method
            // This allows 0 values and malformed coordinates to be auto-corrected
            '1' => ['required'], // Column B: Nama Lengkap
        ];
    }

    public function customValidationMessages()
    {
        return [
            '1.required' => 'Nama Lengkap (Kolom B) wajib diisi.',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '58' => 'Lintang',
            '59' => 'Bujur',
            '1' => 'Nama Lengkap',
        ];
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

    /**
     * Fix malformed coordinate values that are missing decimal points.
     * 
     * This handles cases like:
     * - Latitude: -53343414 → -5.3343414 (Indonesia lat range: roughly -11 to 6)
     * - Longitude: 1052685 → 105.2685 (Indonesia lng range: roughly 95 to 141)
     * 
     * Also allows 0 values to pass through for later correction by operators.
     *
     * @param mixed $value The raw coordinate value
     * @param string $type 'latitude' or 'longitude'
     * @return float|null
     */
    private function fixCoordinate($value, $type = 'latitude')
    {
        // Return null if empty or not set
        if ($value === null || $value === '' || $value === '-') {
            return null;
        }

        // Allow 0 values to pass through (for later correction)
        if ($value === 0 || $value === '0' || $value === 0.0) {
            return 0;
        }

        // If not numeric at all, return null
        if (!is_numeric($value)) {
            return null;
        }

        $numValue = floatval($value);
        $isNegative = $numValue < 0;
        $absValue = abs($numValue);

        // Define valid ranges
        if ($type === 'latitude') {
            // Latitude: -90 to 90 (Indonesia: roughly -11 to 6)
            $maxValid = 90;
            // For Indonesia, we expect values like -5.x to -8.x or 0.x to 6.x
            $expectedIntegerDigits = 1; // Usually 1-2 digits before decimal
        } else {
            // Longitude: -180 to 180 (Indonesia: roughly 95 to 141)
            $maxValid = 180;
            // For Indonesia, we expect values like 95.x to 141.x
            $expectedIntegerDigits = 3; // Usually 2-3 digits before decimal
        }

        // If value is already in valid range, return as-is
        if ($absValue <= $maxValid) {
            return $numValue;
        }

        // Value is out of range - try to fix by inserting decimal point
        // Convert to string without scientific notation
        $strValue = number_format($absValue, 0, '', '');
        
        // Try different decimal positions to find a valid coordinate
        // For latitude: try inserting decimal after 1st or 2nd digit
        // For longitude: try inserting decimal after 2nd or 3rd digit
        $positions = ($type === 'latitude') ? [1, 2] : [2, 3];
        
        foreach ($positions as $pos) {
            if (strlen($strValue) > $pos) {
                $fixedValue = floatval(substr($strValue, 0, $pos) . '.' . substr($strValue, $pos));
                
                // Check if fixed value is in valid range
                if ($fixedValue <= $maxValid) {
                    // Additional check for Indonesia-specific range
                    if ($type === 'latitude' && $fixedValue <= 15) {
                        return $isNegative ? -$fixedValue : $fixedValue;
                    } elseif ($type === 'longitude' && $fixedValue >= 90 && $fixedValue <= 145) {
                        return $isNegative ? -$fixedValue : $fixedValue;
                    } elseif ($type === 'longitude' && $fixedValue <= $maxValid) {
                        return $isNegative ? -$fixedValue : $fixedValue;
                    }
                }
            }
        }

        // If we couldn't fix it, return the original value
        // This allows it to be stored and manually corrected later
        return $numValue;
    }
}
