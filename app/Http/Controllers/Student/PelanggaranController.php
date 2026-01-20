<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PelanggaranSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelanggaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $violations = PelanggaranSiswa::with('masterPelanggaran')
            ->where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalPoints = $violations->sum(function($v) {
            return $v->masterPelanggaran->poin ?? 0;
        });

        return view('student.pelanggaran.index', compact('violations', 'totalPoints', 'siswa'));
    }
}
