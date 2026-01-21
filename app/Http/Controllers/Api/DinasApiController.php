<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DinasApiController extends Controller
{
    /**
     * Get All Schools
     */
    public function schools(Request $request)
    {
        $query = School::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_sekolah', 'like', "%{$search}%")
                  ->orWhere('npsn', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $schools = $query->latest()->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $schools
        ]);
    }

    /**
     * Get School Detail
     */
    public function showSchool($id)
    {
        $school = School::with(['users' => function($q) {
            $q->where('role', 'admin');
        }])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $school
        ]);
    }

    /**
     * Approve School Registration
     */
    public function approveSchool($id)
    {
        $school = School::findOrFail($id);
        $school->update([
            'status' => 'approved',
            'is_active' => true
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Sekolah {$school->nama_sekolah} telah disetujui."
        ]);
    }

    /**
     * Reject School Registration
     */
    public function rejectSchool($id)
    {
        $school = School::findOrFail($id);
        $school->update([
            'status' => 'rejected',
            'is_active' => false
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Sekolah {$school->nama_sekolah} telah ditolak."
        ]);
    }

    /**
     * Toggle School Active Status
     */
    public function toggleSchoolActive($id)
    {
        $school = School::findOrFail($id);
        $school->update([
            'is_active' => !$school->is_active
        ]);

        $status = $school->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return response()->json([
            'status' => 'success',
            'message' => "Sekolah {$school->nama_sekolah} berhasil {$status}."
        ]);
    }

    /**
     * Get Student Statistics
     */
    public function studentStats()
    {
        $totalStudents = DB::table('siswas')->count();
        
        $genderStats = DB::table('siswas')
            ->select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')
            ->get();

        $levelStats = DB::table('siswas')
            ->join('schools', 'siswas.school_id', '=', 'schools.id')
            ->select('schools.jenjang', DB::raw('count(*) as total'))
            ->groupBy('schools.jenjang')
            ->get();

        $statusStats = DB::table('siswas')
            ->join('schools', 'siswas.school_id', '=', 'schools.id')
            ->select('schools.status_sekolah', DB::raw('count(*) as total'))
            ->groupBy('schools.status_sekolah')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_students' => $totalStudents,
                'gender_stats' => $genderStats,
                'level_stats' => $levelStats,
                'status_stats' => $statusStats
            ]
        ]);
    }

    /**
     * Reset School Admin Password
     */
    public function resetAdminPassword($id)
    {
        $school = School::findOrFail($id);
        
        $admins = $school->users()->where('role', 'admin')->get();
        
        if ($admins->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => "Tidak ada admin ditemukan untuk sekolah {$school->nama_sekolah}."
            ], 404);
        }

        foreach ($admins as $admin) {
            $admin->update([
                'password' => bcrypt($school->npsn)
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Password admin untuk sekolah {$school->nama_sekolah} berhasil direset menjadi NPSN ({$school->npsn})."
        ]);

    }
}
