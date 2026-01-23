<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Fungsionaris;
use App\Models\PelanggaranSiswa;
use App\Imports\SchoolImport;
use App\Imports\SiswaImport;
use App\Exports\SchoolTemplateExport;
use App\Models\AppSetting;
use App\Models\LibraryItem;
use App\Models\LibraryLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class DinasController extends Controller
{
    public function settings()
    {
        $settings = AppSetting::first() ?? new AppSetting(['app_name' => 'LITERASIA']);
        return view('admin.dinas.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'school_registration_enabled' => 'sometimes|boolean',
        ]);

        $settings = AppSetting::first() ?? new AppSetting();
        $settings->app_name = $request->app_name;
        $settings->school_registration_enabled = $request->has('school_registration_enabled');

        if ($request->hasFile('app_logo')) {
            // Delete old logo if exists
            if ($settings->app_logo) {
                $oldPath = str_replace('public/', '', $settings->app_logo);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('app_logo')->store('settings', 'public');
            $settings->app_logo = $path;
        }

        $settings->save();

        return back()->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = School::orderBy('created_at', 'desc');

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        // Search by Nama Sekolah or NPSN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_sekolah', 'like', "%{$search}%")
                  ->orWhere('npsn', 'like', "%{$search}%");
            });
        }

        // Filter by Kabupaten/Kota
        if ($request->filled('kabupaten')) {
            $query->where('kabupaten_kota', $request->kabupaten);
        }

        // Filter by Jenjang
        if ($request->filled('jenjang')) {
            $query->where('jenjang', $request->jenjang);
        }

        $schools = $query->paginate(12)->withQueryString();
        $totalCount = School::count();
        $pendingCount = School::where('status', 'pending')->count();
        
        // Get unique kabupaten for filter dropdown
        $kabupatens = School::select('kabupaten_kota')->distinct()->whereNotNull('kabupaten_kota')->orderBy('kabupaten_kota')->pluck('kabupaten_kota');

        return view('admin.dinas.index', compact('schools', 'totalCount', 'pendingCount', 'status', 'kabupatens'));
    }

    public function show(School $school)
    {
        $school->loadCount([
            'siswa as total_siswa' => function($query) {
                $query->withoutGlobalScopes();
            },
            'kelas as total_kelas' => function($query) {
                $query->withoutGlobalScopes();
            },
            'fungsionaris as total_guru' => function($query) {
                $query->withoutGlobalScopes();
            }
        ]);
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
        
        // Filter by Status (Negeri/Swasta)
        if ($request->filled('status')) {
            $query->where('status_sekolah', $request->status);
        }

        // Filter by Approval Status (Pending/Approved/Rejected)
        if ($request->filled('approval_status')) {
            $query->where('status', $request->approval_status);
        }

        // Filter by Kabupaten/Kota
        if ($request->filled('kabupaten')) {
            $query->where('kabupaten_kota', $request->kabupaten);
        }

        // Filter by Jenjang
        if ($request->filled('jenjang')) {
            $query->where('jenjang', $request->jenjang);
        }

        // Search by Nama Sekolah or NPSN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_sekolah', 'like', "%{$search}%")
                  ->orWhere('npsn', 'like', "%{$search}%");
            });
        }

        $schools = $query->latest()->paginate(20)->withQueryString();
        
        // Get unique kabupaten for filter dropdown
        $kabupatens = School::select('kabupaten_kota')->distinct()->whereNotNull('kabupaten_kota')->orderBy('kabupaten_kota')->pluck('kabupaten_kota');
        
        // Count schools without admin accounts
        $schoolsWithoutAccount = School::whereDoesntHave('users', function($q) {
            $q->where('role', 'admin');
        })->count();
        
        return view('admin.dinas.school_data', compact('schools', 'schoolsWithoutAccount', 'kabupatens'));
    }

    public function docs()
    {
        return view('admin.dinas.docs');
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

    public function siswa(Request $request)
    {
        $selectedJenjang = $request->get('jenjang');
        
        $schoolQuery = School::withCount('siswa')->orderBy('nama_sekolah');
        
        if ($selectedJenjang) {
            $schoolQuery->where('jenjang', $selectedJenjang);
        }
        
        $schools = $schoolQuery->get();
        
        $selectedSchoolId = $request->get('school_id');
        $siswas = collect();
        
        if ($selectedSchoolId) {
            $query = Siswa::withoutGlobalScopes()->where('school_id', $selectedSchoolId);

            // Search if provided
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%");
                });
            }

            $siswas = $query->with(['kelas', 'user'])
                ->latest()
                ->paginate($request->get('per_page', 10))
                ->withQueryString();
        }

        return view('admin.dinas.siswa', compact('schools', 'siswas', 'selectedSchoolId', 'selectedJenjang'));
    }

    public function importSiswaForSchool(Request $request, $school_id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new SiswaImport($school_id), $request->file('file'));
            return response()->json(['success' => 'Data siswa berhasil diimport atau diperbarui.']);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return response()->json(['errors' => $errors], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function library(Request $request)
    {
        $selectedJenjang = $request->get('jenjang');
        $selectedSchoolId = $request->get('school_id');
        $selectedCategory = $request->get('category');
        $search = $request->get('search');

        $query = LibraryItem::withoutGlobalScopes()->with('school');

        if ($selectedJenjang) {
            $query->whereHas('school', function ($q) use ($selectedJenjang) {
                $q->where('jenjang', $selectedJenjang);
            });
        }

        if ($selectedSchoolId) {
            $query->where('school_id', $selectedSchoolId);
        }

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()->paginate(20)->withQueryString();

        // Stats for Dinas
        $totalItems = LibraryItem::withoutGlobalScopes()->count();
        $typeStatsRaw = LibraryItem::withoutGlobalScopes()
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();
            
        $typeStats = [
            'book' => $typeStatsRaw->firstWhere('category', 'book')->total ?? 0,
            'audio' => $typeStatsRaw->firstWhere('category', 'audio')->total ?? 0,
            'video' => $typeStatsRaw->firstWhere('category', 'video')->total ?? 0,
        ];

        $schoolStats = School::select('schools.id', 'schools.nama_sekolah', 'schools.jenjang', DB::raw('count(library_items.id) as total_items'))
            ->leftJoin('library_items', 'schools.id', '=', 'library_items.school_id')
            ->groupBy('schools.id', 'schools.nama_sekolah', 'schools.jenjang')
            ->orderBy('nama_sekolah', 'asc')
            ->get();

        $schools = $schoolStats; // Reuse schoolStats for the dropdown to have counts

        return view('admin.dinas.library', compact(
            'items', 
            'totalItems', 
            'typeStats', 
            'schoolStats', 
            'schools',
            'selectedJenjang',
            'selectedSchoolId',
            'selectedCategory',
            'search'
        ));
    }
}

