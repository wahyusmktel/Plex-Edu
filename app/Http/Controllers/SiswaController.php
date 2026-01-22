<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Exports\SiswaTemplateExport;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['user', 'kelas']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Filter Kelas
        if ($request->has('kelas') && $request->kelas != '') {
            $query->where('kelas_id', $request->kelas);
        }

        // Pagination Custom
        $perPage = $request->get('per_page', 10);
        $siswas = $query->latest()->paginate($perPage)->withQueryString();

        $kelas = Kelas::all();
        
        // Count siswa without user accounts
        $withoutAccount = Siswa::whereNull('user_id')->count();
        
        return view('admin.siswa.index', compact('siswas', 'kelas', 'withoutAccount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'nis' => 'required|unique:siswas,nis',
            'nisn' => 'required|unique:siswas,nisn',
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama_lengkap,
                'username' => $request->username,
                'email' => $request->username . '@siswa.literasia.org',
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);

            $data = $request->all();
            $data['user_id'] = $user->id;
            
            Siswa::create($data);
        });

        return response()->json(['success' => 'Data siswa berhasil disimpan']);
    }

    public function edit($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        
        $request->validate([
            'nama_lengkap' => 'required',
            'nis' => 'required|unique:siswas,nis,' . $id . ',id',
            'nisn' => 'required|unique:siswas,nisn,' . $id . ',id',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        if ($siswa->user_id) {
            $request->validate([
                'username' => 'required|unique:users,username,' . $siswa->user_id . ',id',
            ]);
        }

        DB::transaction(function () use ($request, $siswa) {
            if ($siswa->user_id) {
                $user = User::findOrFail($siswa->user_id);
                $userUpdate = [
                    'name' => $request->nama_lengkap,
                    'username' => $request->username,
                    'email' => $request->username . '@siswa.literasia.org',
                ];
                if ($request->password) {
                    $userUpdate['password'] = Hash::make($request->password);
                }
                $user->update($userUpdate);
            }

            $siswa->update($request->all());
        });

        if ($request->ajax()) {
            return response()->json(['success' => 'Data siswa berhasil diupdate']);
        }

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        User::where('id', $siswa->user_id)->delete();
        $siswa->delete();
        return response()->json(['success' => 'Data siswa berhasil dihapus']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));
            return response()->json(['success' => 'Data siswa berhasil diimport']);
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

    public function generateAccounts()
    {
        // Get siswa without user account
        $siswaList = Siswa::whereNull('user_id')->get();

        $total = $siswaList->count();
        $generated = 0;
        $errors = [];

        foreach ($siswaList as $siswa) {
            try {
                $email = $siswa->nisn . '@siswa.literasia.org';
                
                // Skip if email already exists
                if (User::where('email', $email)->exists()) {
                    $errors[] = "NISN {$siswa->nisn}: Email sudah terdaftar";
                    continue;
                }

                $user = User::create([
                    'name' => $siswa->nama_lengkap,
                    'username' => $siswa->nisn,
                    'email' => $email,
                    'password' => Hash::make($siswa->nisn), // Password is NISN
                    'role' => 'siswa',
                ]);

                $siswa->update(['user_id' => $user->id]);
                $generated++;
            } catch (\Exception $e) {
                $errors[] = "{$siswa->nama_lengkap}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'total' => $total,
            'generated' => $generated,
            'errors' => $errors,
            'message' => "{$generated} akun siswa berhasil di-generate dari {$total} data."
        ]);
    }

    public function resetPassword($id)
    {
        $siswa = Siswa::findOrFail($id);
        
        if (!$siswa->user_id) {
            return response()->json(['error' => 'Siswa ini belum memiliki akun.'], 404);
        }

        $user = User::find($siswa->user_id);
        if (!$user) {
            return response()->json(['error' => 'Akun user tidak ditemukan.'], 404);
        }

        $user->update([
            'password' => Hash::make($siswa->nisn), // Reset to NISN
            'email' => $siswa->nisn . '@siswa.literasia.org', // Update email domain too
        ]);

        return response()->json([
            'success' => true,
            'message' => "Password {$siswa->nama_lengkap} berhasil direset ke NISN ({$siswa->nisn})."
        ]);
    }

    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplateExport, 'template_import_siswa.xlsx');
    }

    public function show($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        return response()->json($siswa);
    }
}

