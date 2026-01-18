<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ERaport;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ERaportApiController extends Controller
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

        $raports = ERaport::withoutGlobalScope('school')
            ->where('siswa_id', $siswa->id)
            ->orderBy('tahun_pelajaran', 'desc')
            ->orderBy('semester', 'desc')
            ->get()
            ->map(function ($raport) {
                return [
                    'id' => $raport->id,
                    'semester' => $raport->semester,
                    'tahun_pelajaran' => $raport->tahun_pelajaran,
                    'file_name' => $raport->file_name,
                    'file_url' => asset('storage/' . $raport->file_path),
                    'created_at' => $raport->created_at->format('d M Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'siswa' => [
                    'nama' => $siswa->nama_lengkap,
                    'nis' => $siswa->nis,
                    'nisn' => $siswa->nisn,
                    'kelas' => $siswa->kelas?->nama ?? 'N/A',
                ],
                'raports' => $raports
            ]
        ]);
    }

    public function show($id)
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

        $raport = ERaport::withoutGlobalScope('school')
            ->where('siswa_id', $siswa->id)
            ->where('id', $id)
            ->first();

        if (!$raport) {
            return response()->json([
                'success' => false,
                'message' => 'E-Raport tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $raport->id,
                'semester' => $raport->semester,
                'tahun_pelajaran' => $raport->tahun_pelajaran,
                'file_name' => $raport->file_name,
                'file_url' => asset('storage/' . $raport->file_path),
                'created_at' => $raport->created_at->format('d M Y H:i'),
            ]
        ]);
    }
}
