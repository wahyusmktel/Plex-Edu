<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Fungsionaris;
use App\Models\TeacherCertificate;
use App\Models\PelanggaranSiswa;
use App\Models\Cbt;
use App\Models\Subject;
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

        /**
     * Get Teacher Certificate Monitoring
     */
    public function teacherCertificates(Request $request)
    {
        $query = User::withoutGlobalScopes()
            ->where('role', 'guru')
            ->with(['fungsionaris', 'school'])
            ->withCount('teacherCertificates');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('school', function($sq) use ($search) {
                      $sq->where('nama_sekolah', 'like', "%{$search}%");
                  });
            });
        }

        $teachers = $query->latest()->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $teachers
        ]);
    }

    /**
     * Get Teacher Certificate Details
     */
    public function teacherCertificateDetails($id)
    {
        $teacher = User::withoutGlobalScopes()
            ->where('role', 'guru')
            ->with(['fungsionaris', 'school'])
            ->findOrFail($id);

        if (!$teacher->fungsionaris) {
             return response()->json([
                'status' => 'error',
                'message' => "Profil fungsionaris tidak ditemukan untuk guru ini."
            ], 404);
        }

        $certificates = TeacherCertificate::withoutGlobalScopes()
            ->where('teacher_id', $teacher->fungsionaris->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'teacher' => $teacher,
                'certificates' => $certificates
            ]
        ]);
    }

    /**
     * Get Global Violation Monitoring
     */
    public function violations(Request $request)
    {
        $query = PelanggaranSiswa::withoutGlobalScopes()
            ->with(['siswa', 'school', 'masterPelanggaran']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($sq) use ($search) {
                $sq->where('nama_lengkap', 'like', "%{$search}%");
            })->orWhereHas('school', function($sq) use ($search) {
                $sq->where('nama_sekolah', 'like', "%{$search}%");
            });
        }

        $violations = $query->latest()->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $violations
        ]);
    }

    /**
     * Get Global CBT List
     */
    public function getGlobalCbts(Request $request)
    {
        $cbts = Cbt::withoutGlobalScope('school')
            ->whereNull('school_id')
            ->with(['subject' => function($q) {
                $q->withoutGlobalScope('school');
            }, 'creator'])
            ->latest()
            ->get()
            ->map(function($cbt) {
                return [
                    'id' => $cbt->id,
                    'nama_cbt' => $cbt->nama_cbt,
                    'subject' => $cbt->subject?->nama_pelajaran ?? 'N/A',
                    'subject_id' => $cbt->subject_id,
                    'tanggal' => $cbt->tanggal->format('Y-m-d'),
                    'jam_mulai' => $cbt->jam_mulai,
                    'jam_selesai' => $cbt->jam_selesai,
                    'token' => $cbt->token,
                    'skor_maksimal' => $cbt->skor_maksimal,
                    'show_result' => $cbt->show_result,
                    'questions_count' => $cbt->questions()->count(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $cbts
        ]);
    }

    /**
     * Store Global CBT
     */
    public function storeCbt(Request $request)
    {
        if (auth()->user()->role !== 'dinas') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'nama_cbt' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'subject_id' => 'required|exists:subjects,id',
            'skor_maksimal' => 'required|integer|min:1',
            'show_result' => 'boolean',
        ]);

        $cbt = Cbt::create([
            'nama_cbt' => $request->nama_cbt,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'subject_id' => $request->subject_id,
            'skor_maksimal' => $request->skor_maksimal,
            'show_result' => $request->boolean('show_result'),
            'participant_type' => 'all', // Global CBT is for everyone
            'created_by' => auth()->id(),
            'school_id' => null, // Explicitly null for global
        ]);

        return response()->json([
            'success' => true,
            'message' => 'CBT Global berhasil ditambahkan',
            'data' => $cbt
        ]);
    }

    /**
     * Update Global CBT
     */
    public function updateCbt(Request $request, $id)
    {
        if (auth()->user()->role !== 'dinas') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $cbt = Cbt::withoutGlobalScope('school')->whereNull('school_id')->findOrFail($id);

        $request->validate([
            'nama_cbt' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'subject_id' => 'required|exists:subjects,id',
            'skor_maksimal' => 'required|integer|min:1',
            'show_result' => 'boolean',
        ]);

        $cbt->update([
            'nama_cbt' => $request->nama_cbt,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'subject_id' => $request->subject_id,
            'skor_maksimal' => $request->skor_maksimal,
            'show_result' => $request->boolean('show_result'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'CBT Global berhasil diperbarui'
        ]);
    }

    /**
     * Delete Global CBT
     */
    public function destroyCbt($id)
    {
        if (auth()->user()->role !== 'dinas') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $cbt = Cbt::withoutGlobalScope('school')->whereNull('school_id')->findOrFail($id);
        $cbt->delete();

        return response()->json([
            'success' => true,
            'message' => 'CBT Global berhasil dihapus'
        ]);
    }

    /**
     * Get Global Subjects for CBT
     */
    public function getGlobalSubjects()
    {
        $subjects = Subject::withoutGlobalScope('school')
            ->whereNull('school_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $subjects
        ]);
    }
}

