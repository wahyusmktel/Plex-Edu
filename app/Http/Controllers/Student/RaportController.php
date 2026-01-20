<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ERaport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RaportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $raports = ERaport::where('siswa_id', $siswa->id)
            ->orderBy('tahun_pelajaran', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return view('student.raport.index', compact('raports', 'siswa'));
    }
}
