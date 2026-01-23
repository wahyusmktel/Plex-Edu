<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterGuruDinas;
use App\Models\School;
use App\Imports\MasterGuruDinasImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class GuruDinasController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterGuruDinas::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('tempat_tugas', 'like', "%{$search}%");
            });
        }

        if ($request->filled('npsn')) {
            $query->where('npsn', $request->npsn);
        }

        $gurus = $query->latest()->paginate(25)->withQueryString();
        $schools = MasterGuruDinas::select('npsn', 'tempat_tugas')->distinct()->orderBy('tempat_tugas')->get();

        return view('admin.dinas.guru.index', compact('gurus', 'schools'));
    }

    public function sync(Request $request)
    {
        $schools = School::orderBy('nama_sekolah')->get();
        $selectedSchool = null;
        $previewData = collect();

        if ($request->filled('school_id')) {
            $selectedSchool = School::findOrFail($request->school_id);
            $previewData = MasterGuruDinas::where('npsn', $selectedSchool->npsn)->get();
        }

        return view('admin.dinas.guru.sync', compact('schools', 'selectedSchool', 'previewData'));
    }

    public function processSync(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'ids' => 'required|array',
        ]);

        $school = School::findOrFail($request->school_id);
        $masterData = MasterGuruDinas::whereIn('id', $request->ids)->get();
        $synced = 0;
        $skipped = 0;

        foreach ($masterData as $data) {
            // Check if already exists in fungsionaris for THIS school
            $exists = \App\Models\Fungsionaris::where('school_id', $school->id)
                ->where(function($q) use ($data) {
                    if ($data->nik) $q->orWhere('nik', $data->nik);
                    if ($data->nip) $q->orWhere('nip', $data->nip);
                })->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            \App\Models\Fungsionaris::create([
                'school_id' => $school->id,
                'user_id' => null,
                'nama' => $data->nama,
                'nik' => $data->nik,
                'nip' => $data->nip,
                'jenis_kelamin' => $data->jenis_kelamin,
                'tempat_lahir' => $data->tempat_lahir,
                'tanggal_lahir' => $data->tanggal_lahir,
                'no_hp' => $data->no_hp,
                'jabatan' => str_contains(strtolower($data->jenis_ptk), 'guru') ? 'guru' : 'pegawai',
                'posisi' => $data->jabatan_ptk,
                'status' => 'aktif',
                'pendidikan_terakhir' => $data->pendidikan,
            ]);
            $synced++;
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil menyalin {$synced} data guru ke {$school->nama_sekolah}. ({$skipped} data dilewati karena sudah ada)"
        ]);
    }

    public function show($id)
    {
        $guru = MasterGuruDinas::findOrFail($id);
        return view('admin.dinas.guru.show', compact('guru'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new MasterGuruDinasImport, $request->file('file'));
            return response()->json(['success' => 'Master data guru berhasil diimport.']);
        } catch (ValidationException $e) {
            $errors = collect($e->failures())->map(function ($failure) {
                return 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            })->implode(' | ');
            return response()->json(['error' => $errors ?: 'Gagal import data guru.'], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal import data guru: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $guru = MasterGuruDinas::findOrFail($id);
        $guru->delete();
        return back()->with('success', 'Data guru berhasil dihapus.');
    }

    public function clear()
    {
        MasterGuruDinas::truncate();
        return back()->with('success', 'Semua master data guru berhasil dikosongkan.');
    }
}
