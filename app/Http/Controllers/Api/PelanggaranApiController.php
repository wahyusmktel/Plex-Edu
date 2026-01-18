<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelanggaranApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $siswa = Siswa::withoutGlobalScope('school')
            ->where('user_id', $user->id)
            ->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        $pelanggaran = PelanggaranSiswa::withoutGlobalScope('school')
            ->with(['masterPelanggaran' => function($q) {
                $q->withoutGlobalScope('school');
            }])
            ->where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nama_pelanggaran' => $p->masterPelanggaran?->nama ?? 'N/A',
                    'jenis' => $p->masterPelanggaran?->jenis ?? 'N/A',
                    'poin' => $p->masterPelanggaran?->poin ?? 0,
                    'tanggal' => \Carbon\Carbon::parse($p->tanggal)->format('d M Y'),
                    'deskripsi' => $p->deskripsi,
                    'tindak_lanjut' => $p->tindak_lanjut,
                ];
            });

        $totalPoin = $pelanggaran->sum('poin');

        return response()->json([
            'success' => true,
            'data' => [
                'siswa' => [
                    'nama' => $siswa->nama_lengkap,
                    'nis' => $siswa->nis,
                    'kelas' => $siswa->kelas?->nama ?? 'N/A',
                ],
                'total_poin' => $totalPoin,
                'pelanggaran' => $pelanggaran
            ]
        ]);
    }
}
