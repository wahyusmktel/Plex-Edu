<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Fungsionaris;
use App\Models\PelanggaranSiswa;
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

    public function stats()
    {
        // Global student statistics across ALL schools
        $totalSiswa = Siswa::withoutGlobalScopes()->count();
        $genderStats = Siswa::withoutGlobalScopes()
            ->select('jenis_kelamin', \DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')
            ->get();
            
        $schoolGrowth = School::select(\DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), \DB::raw('count(*) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dinas.statistics_siswa', compact('totalSiswa', 'genderStats', 'schoolGrowth'));
    }

    public function schools(Request $request)
    {
        $query = School::query();
        
        if ($request->has('status')) {
            $query->where('status_sekolah', $request->status); // Negeri/Swasta
        }

        $schools = $query->latest()->paginate(20);
        return view('admin.dinas.school_data', compact('schools'));
    }

    public function certificates()
    {
        // View for teacher certificates across all schools
        // This might need a Certificate model if one exists, 
        // otherwise we just list teachers with their status.
        $teachers = User::withoutGlobalScopes()->where('role', 'guru')->with('fungsionaris')->paginate(30);
        return view('admin.dinas.teacher_certificates', compact('teachers'));
    }

    public function violations()
    {
        // Global violation monitoring
        $violations = PelanggaranSiswa::withoutGlobalScopes()->with(['siswa', 'school'])->latest()->paginate(30);
        return view('admin.dinas.violations', compact('violations'));
    }
}
