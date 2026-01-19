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

    private function findKey(array $row, array $parts): ?string
    {
        foreach (array_keys($row) as $key) {
            $normalized = $this->normalizeKey($key);
            $matched = true;
            foreach ($parts as $part) {
                if (!str_contains($normalized, $part)) {
                    $matched = false;
                    break;
                }
            }
            if ($matched) {
                return $key;
            }
        }

        return null;
    }

    public function prepareForValidation($data, $index)
    {
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
            if (!array_key_exists($target, $data) || $data[$target] === null) {
                $found = $this->findKey($data, $parts);
                if ($found !== null) {
                    $data[$target] = $data[$found];
                }
            }
        }

        if (isset($data['npsn'])) {
            $data['npsn'] = trim((string) $data['npsn']);
        }

        return $data;
    }

    public function model(array $row)
    {
        $npsn = isset($row['npsn']) ? trim((string) $row['npsn']) : null;
        $statusRaw = strtolower(trim((string) ($row['status_sekolah'] ?? 'swasta')));
        $status = str_contains($statusRaw, 'negeri') ? 'Negeri' : 'Swasta';

        // Only create school data, user account will be generated separately
        return new School([
            'nama_sekolah' => $row['nama_sekolah'] ?? null,
            'npsn' => $npsn,
            'status_sekolah' => $status,
            'provinsi' => $row['provinsi'] ?? null,
            'kabupaten_kota' => $row['kabupaten_kota'] ?? null,
            'kecamatan' => $row['kecamatan'] ?? null,
            'desa_kelurahan' => $row['desa_kelurahan'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'jenjang' => $row['jenjang'] ?? null,
            'latitude' => $row['latitude'] ?? null,
            'longitude' => $row['longitude'] ?? null,
            'status' => 'approved',
            'is_active' => true, // Set is_active to true on import
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
