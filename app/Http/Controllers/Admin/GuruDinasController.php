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
