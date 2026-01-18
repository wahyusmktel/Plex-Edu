<?php

namespace App\Imports;

use App\Models\BankSoalQuestion;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BankSoalQuestionImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private string $bankSoalId;

    public function __construct(string $bankSoalId)
    {
        $this->bankSoalId = $bankSoalId;
    }

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
            'jenis_soal' => ['jenis', 'soal'],
            'pertanyaan' => ['pertanyaan'],
            'poin' => ['poin'],
            'opsi_a' => ['opsi', 'a'],
            'opsi_b' => ['opsi', 'b'],
            'opsi_c' => ['opsi', 'c'],
            'opsi_d' => ['opsi', 'd'],
            'opsi_e' => ['opsi', 'e'],
            'jawaban' => ['jawaban'],
            'kunci' => ['kunci'],
        ];

        foreach ($map as $target => $parts) {
            if (!array_key_exists($target, $data) || $data[$target] === null) {
                $found = $this->findKey($data, $parts);
                if ($found !== null) {
                    $data[$target] = $data[$found];
                }
            }
        }

        if (empty($data['jawaban']) && !empty($data['kunci'])) {
            $data['jawaban'] = $data['kunci'];
        }

        $jenisRaw = strtolower(trim((string) ($data['jenis_soal'] ?? '')));
        if ($jenisRaw === '') {
            $hasOptions = collect(['opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e'])
                ->contains(fn ($key) => !empty($data[$key]));
            $data['jenis_soal'] = $hasOptions ? 'pilihan_ganda' : 'essay';
        } elseif (str_contains($jenisRaw, 'essay') || str_contains($jenisRaw, 'uraian')) {
            $data['jenis_soal'] = 'essay';
        } else {
            $data['jenis_soal'] = 'pilihan_ganda';
        }

        if (isset($data['jawaban'])) {
            $jawaban = strtoupper(trim((string) $data['jawaban']));
            $mapJawaban = [
                '1' => 'A',
                '2' => 'B',
                '3' => 'C',
                '4' => 'D',
                '5' => 'E',
            ];
            $data['jawaban'] = $mapJawaban[$jawaban] ?? $jawaban;
        }

        return $data;
    }

    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            $question = BankSoalQuestion::create([
                'bank_soal_id' => $this->bankSoalId,
                'jenis_soal' => $row['jenis_soal'],
                'pertanyaan' => $row['pertanyaan'],
                'poin' => (int) ($row['poin'] ?? 0),
            ]);

            if ($row['jenis_soal'] === 'pilihan_ganda') {
                $opsi = [
                    'A' => $row['opsi_a'] ?? null,
                    'B' => $row['opsi_b'] ?? null,
                    'C' => $row['opsi_c'] ?? null,
                    'D' => $row['opsi_d'] ?? null,
                    'E' => $row['opsi_e'] ?? null,
                ];

                $jawaban = strtoupper(trim((string) ($row['jawaban'] ?? '')));

                foreach ($opsi as $label => $text) {
                    if (!empty($text)) {
                        $question->options()->create([
                            'opsi' => $text,
                            'is_correct' => $jawaban === $label,
                        ]);
                    }
                }
            }

            return $question;
        });
    }

    public function rules(): array
    {
        return [
            '*.jenis_soal' => 'required|in:pilihan_ganda,essay',
            '*.pertanyaan' => 'required|string',
            '*.poin' => 'required|integer|min:0',
            '*.opsi_a' => 'required_if:jenis_soal,pilihan_ganda|string|nullable',
            '*.opsi_b' => 'required_if:jenis_soal,pilihan_ganda|string|nullable',
            '*.jawaban' => 'required_if:jenis_soal,pilihan_ganda|in:A,B,C,D,E',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.jenis_soal.required' => 'Jenis soal wajib diisi.',
            '*.jenis_soal.in' => 'Jenis soal harus pilihan_ganda atau essay.',
            '*.pertanyaan.required' => 'Pertanyaan wajib diisi.',
            '*.poin.required' => 'Poin wajib diisi.',
            '*.opsi_a.required_if' => 'Opsi A wajib diisi untuk soal pilihan ganda.',
            '*.opsi_b.required_if' => 'Opsi B wajib diisi untuk soal pilihan ganda.',
            '*.jawaban.required_if' => 'Jawaban wajib diisi untuk soal pilihan ganda.',
            '*.jawaban.in' => 'Jawaban harus A, B, C, D, atau E.',
        ];
    }
}
