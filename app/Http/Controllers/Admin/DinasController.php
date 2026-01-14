<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class DinasController extends Controller
{
    public function index()
    {
        $schools = School::orderBy('created_at', 'desc')->get();
        return view('admin.dinas.index', compact('schools'));
    }

    public function show(School $school)
    {
        return view('admin.dinas.show', compact('school'));
    }

    public function approve(School $school)
    {
        $school->update([
            'status' => 'approved',
            'is_active' => true
        ]);

        return back()->with('success', "Sekolah {$school->nama_sekolah} telah disetujui.");
    }

    public function reject(School $school, Request $request)
    {
        $school->update([
            'status' => 'rejected',
            'is_active' => false
        ]);

        return back()->with('warning', "Sekolah {$school->nama_sekolah} telah ditolak.");
    }

    public function toggleActive(School $school)
    {
        $school->update([
            'is_active' => !$school->is_active
        ]);

        $status = $school->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Sekolah {$school->nama_sekolah} berhasil {$status}.");
    }
}
