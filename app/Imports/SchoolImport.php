<?php

namespace App\Imports;

use App\Models\School;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SchoolImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private function normalizeKey(string $key): string
    {
        return preg_replace('/[^a-z0-9]+/', '', strtolower($key));
    }

    public function prepareForValidation($data, $index)
    {
        // 1. Create a normalized version of the row for easier matching
        $normalizedRow = [];
        foreach ($data as $key => $value) {
            $normalizedRow[$this->normalizeKey((string)$key)] = $value;
        }

        $map = [
            'nama_sekolah' => ['nama', 'sekolah'],
            'npsn' => ['npsn'],
            'status_sekolah' => ['status', 'sekolah'],
            'provinsi' => ['provinsi'],
            'kabupaten_kota' => ['kabupaten', 'kota'],
            'kecamatan' => ['kecamatan'],
            'desa_kelurahan' => ['desa', 'kelurahan'],
            'alamat' => ['alamat'],
            'jenjang' => ['jenjang'],
            'latitude' => ['lintang', 'latitude', 'lat'],
            'longitude' => ['bujur', 'longitude', 'lng'],
        ];

        foreach ($map as $target => $parts) {
            // Already set by heading row slug?
            if (isset($data[$target]) && $data[$target] !== null && $data[$target] !== '') {
                continue;
            }

            // Try exact match with normalized parts (e.g., 'lintang')
            foreach ($parts as $part) {
                if (array_key_exists($part, $normalizedRow)) {
                    $data[$target] = $normalizedRow[$part];
                    goto next_target;
                }
            }

            // Fallback: Multi-part match (e.g., 'nama' and 'sekolah' in 'nama_sekolah')
            foreach ($normalizedRow as $normKey => $val) {
                $allMatched = true;
                foreach ($parts as $part) {
                    if (!str_contains($normKey, $part)) {
                        $allMatched = false;
                        break;
                    }
                }
                if ($allMatched) {
                    $data[$target] = $val;
                    goto next_target;
                }
            }

            next_target:
        }

        if (isset($data['npsn'])) {
            $data['npsn'] = trim((string) $data['npsn']);
        }

        return $data;
    }

    public function model(array $row)
    {
        // Re-run mapping logic so model has access to the same keys as rules()
        $mapped = $this->prepareForValidation($row, 0);

        $npsn = isset($mapped['npsn']) ? trim((string) $mapped['npsn']) : null;
        $statusRaw = strtolower(trim((string) ($mapped['status_sekolah'] ?? 'swasta')));
        $status = str_contains($statusRaw, 'negeri') ? 'Negeri' : 'Swasta';

        return new School([
            'nama_sekolah' => $mapped['nama_sekolah'] ?? null,
            'npsn' => $npsn,
            'status_sekolah' => $status,
            'provinsi' => $mapped['provinsi'] ?? null,
            'kabupaten_kota' => $mapped['kabupaten_kota'] ?? null,
            'kecamatan' => $mapped['kecamatan'] ?? null,
            'desa_kelurahan' => $mapped['desa_kelurahan'] ?? null,
            'alamat' => $mapped['alamat'] ?? null,
            'jenjang' => $mapped['jenjang'] ?? null,
            'latitude' => $mapped['latitude'] ?? null,
            'longitude' => $mapped['longitude'] ?? null,
            'status' => 'approved',
            'is_active' => true,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_sekolah' => 'required|string|max:255',
            '*.npsn' => 'required|max:20|unique:schools,npsn',
            '*.status_sekolah' => 'required|in:Negeri,Swasta,negeri,swasta',
            '*.provinsi' => 'required|string|max:255',
            '*.kabupaten_kota' => 'required|string|max:255',
            '*.kecamatan' => 'required|string|max:255',
            '*.desa_kelurahan' => 'required|string|max:255',
            '*.alamat' => 'required|string',
            '*.jenjang' => 'required|in:sd,smp,sma_smk',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nama_sekolah.required' => 'Nama sekolah wajib diisi.',
            '*.npsn.required' => 'NPSN wajib diisi.',
            '*.npsn.unique' => 'NPSN :input sudah terdaftar.',
            '*.status_sekolah.in' => 'Status sekolah harus Negeri atau Swasta.',
            '*.provinsi.required' => 'Provinsi wajib diisi.',
            '*.kabupaten_kota.required' => 'Kabupaten/Kota wajib diisi.',
            '*.kecamatan.required' => 'Kecamatan wajib diisi.',
            '*.desa_kelurahan.required' => 'Desa/Kelurahan wajib diisi.',
            '*.alamat.required' => 'Alamat wajib diisi.',
        ];
    }
}
