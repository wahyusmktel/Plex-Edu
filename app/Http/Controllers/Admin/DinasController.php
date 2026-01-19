<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Fungsionaris;
use App\Models\PelanggaranSiswa;
use App\Imports\SchoolImport;
use App\Exports\SchoolTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

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
        
        // Count schools without admin accounts
        $schoolsWithoutAccount = School::whereDoesntHave('users', function($q) {
            $q->where('role', 'admin');
        })->count();
        
        return view('admin.dinas.school_data', compact('schools', 'schoolsWithoutAccount'));
    }

    public function storeSchool(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'required|max:20|unique:schools,npsn|unique:users,username',
            'jenjang' => 'required|in:sd,smp,sma_smk',
            'status_sekolah' => 'required|in:Negeri,Swasta',
            'provinsi' => 'required|string|max:255',
            'kabupaten_kota' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'desa_kelurahan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $email = $request->npsn . '@admin.literasia.org';
        if (User::where('email', $email)->exists()) {
            return back()->with('error', 'Email admin sekolah sudah terdaftar.');
        }

        DB::transaction(function () use ($request, $email) {
            $school = School::create([
                'nama_sekolah' => $request->nama_sekolah,
                'npsn' => $request->npsn,
                'jenjang' => $request->jenjang,
                'status_sekolah' => $request->status_sekolah,
                'provinsi' => $request->provinsi,
                'kabupaten_kota' => $request->kabupaten_kota,
                'kecamatan' => $request->kecamatan,
                'desa_kelurahan' => $request->desa_kelurahan,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => 'approved',
                'is_active' => true,
            ]);

            User::create([
                'school_id' => $school->id,
                'name' => $school->nama_sekolah,
                'email' => $email,
                'username' => $request->npsn,
                'password' => Hash::make($request->npsn),
                'role' => 'admin',
            ]);
        });

        return back()->with('success', 'Data sekolah berhasil ditambahkan beserta akun admin.');
    }

    public function importSchools(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new SchoolImport, $request->file('file'));
            return response()->json(['success' => 'Data sekolah berhasil diimport.']);
        } catch (ValidationException $e) {
            $errors = collect($e->failures())->map(function ($failure) {
                return 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            })->implode(' | ');

            return response()->json(['error' => $errors ?: 'Gagal import data sekolah.'], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal import data sekolah: ' . $e->getMessage()], 500);
        }
    }

    public function generateSchoolAccounts(Request $request)
    {
        // Get schools without admin account
        $schools = School::whereDoesntHave('users', function($q) {
            $q->where('role', 'admin');
        })->get();

        $total = $schools->count();
        $generated = 0;
        $errors = [];

        foreach ($schools as $school) {
            try {
                $email = $school->npsn . '@admin.literasia.org';
                
                // Skip if email already exists
                if (User::where('email', $email)->exists()) {
                    $errors[] = "NPSN {$school->npsn}: Email sudah terdaftar";
                    continue;
                }

                User::create([
                    'school_id' => $school->id,
                    'name' => $school->nama_sekolah,
                    'email' => $email,
                    'username' => $school->npsn,
                    'password' => Hash::make($school->npsn),
                    'role' => 'admin',
                ]);

                $generated++;
            } catch (\Exception $e) {
                $errors[] = "NPSN {$school->npsn}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'total' => $total,
            'generated' => $generated,
            'errors' => $errors,
            'message' => "{$generated} akun admin sekolah berhasil di-generate dari {$total} sekolah."
        ]);
    }

    public function resetSchoolPassword(School $school)
    {
        $user = User::where('school_id', $school->id)->where('role', 'admin')->first();
        
        if (!$user) {
            return response()->json(['error' => 'Akun admin sekolah tidak ditemukan.'], 404);
        }

        $user->update([
            'password' => Hash::make($school->npsn),
            'email' => $school->npsn . '@admin.literasia.org', // Update email domain too
        ]);

        return response()->json([
            'success' => true,
            'message' => "Password akun {$school->nama_sekolah} berhasil direset ke NPSN ({$school->npsn})."
        ]);
    }

    public function downloadSchoolTemplate()
    {
        return Excel::download(new SchoolTemplateExport, 'template_import_sekolah.xlsx');
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

