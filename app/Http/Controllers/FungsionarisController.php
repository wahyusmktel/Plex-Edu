<?php

namespace App\Http\Controllers;

use App\Models\Fungsionaris;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FungsionarisImport;
use App\Exports\FungsionarisTemplateExport;
use App\Exports\FungsionarisExport;

class FungsionarisController extends Controller
{
    public function index()
    {
        $guru = Fungsionaris::where('jabatan', 'guru')->with('user')->get();
        $pegawai = Fungsionaris::where('jabatan', 'pegawai')->with('user')->get();
        
        // Count fungsionaris without user accounts
        $withoutAccount = Fungsionaris::whereNull('user_id')->count();
        
        return view('admin.fungsionaris.index', compact('guru', 'pegawai', 'withoutAccount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nip' => 'required|unique:fungsionaris,nip',
            'nik' => 'required|unique:fungsionaris,nik',
            'posisi' => 'required',
            'jabatan' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'status' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'school_id' => auth()->user()->school_id,
                'name' => $request->nama,
                'username' => $request->username,
                'email' => $request->username . '@guru.literasia.org',
                'password' => Hash::make($request->password),
                'role' => $request->jabatan === 'guru' ? 'guru' : 'pegawai',
            ]);

            Fungsionaris::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nik' => $request->nik,
                'nama' => $request->nama,
                'posisi' => $request->posisi,
                'jabatan' => $request->jabatan,
                'status' => $request->status,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
            ]);
        });

        return response()->json(['success' => 'Data berhasil disimpan']);
    }

    public function update(Request $request, $id)
    {
        $fungsionaris = Fungsionaris::findOrFail($id);
        
        $request->validate([
            'nama' => 'required',
            'nip' => 'required|unique:fungsionaris,nip,' . $id . ',id',
            'nik' => 'required|unique:fungsionaris,nik,' . $id . ',id',
            'posisi' => 'required',
            'jabatan' => 'required',
            'username' => 'required|unique:users,username,' . $fungsionaris->user_id . ',id',
            'status' => 'required',
        ]);

        DB::transaction(function () use ($request, $fungsionaris) {
            $user = User::findOrFail($fungsionaris->user_id);
            $user->update([
                'name' => $request->nama,
                'username' => $request->username,
                'email' => $request->username . '@guru.literasia.org',
                'role' => $request->jabatan === 'guru' ? 'guru' : 'pegawai',
            ]);

            if ($request->password) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $fungsionaris->update([
                'nip' => $request->nip,
                'nik' => $request->nik,
                'nama' => $request->nama,
                'posisi' => $request->posisi,
                'jabatan' => $request->jabatan,
                'status' => $request->status,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
            ]);
        });

        return response()->json(['success' => 'Data berhasil diupdate']);
    }

    public function destroy($id)
    {
        $fungsionaris = Fungsionaris::findOrFail($id);
        User::where('id', $fungsionaris->user_id)->delete();
        $fungsionaris->delete();
        return response()->json(['success' => 'Data berhasil dihapus']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new FungsionarisImport, $request->file('file'));
            return response()->json(['success' => 'Data berhasil diimport']);
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
        $schoolId = auth()->user()->school_id;
        
        // Get fungsionaris without user account
        $fungsionarisList = Fungsionaris::where('school_id', $schoolId)
            ->whereNull('user_id')
            ->get();

        $total = $fungsionarisList->count();
        $generated = 0;
        $errors = [];

        foreach ($fungsionarisList as $fungsionaris) {
            try {
                // Generate random number for email
                $randomNumber = mt_rand(100000, 999999);
                $email = $randomNumber . '@guru.literasia.org';
                
                // Make sure email is unique
                while (User::where('email', $email)->exists()) {
                    $randomNumber = mt_rand(100000, 999999);
                    $email = $randomNumber . '@guru.literasia.org';
                }

                $user = User::create([
                    'school_id' => $schoolId,
                    'name' => $fungsionaris->nama,
                    'username' => (string)$randomNumber,
                    'email' => $email,
                    'password' => Hash::make('literasia'),
                    'role' => $fungsionaris->jabatan,
                ]);

                $fungsionaris->update(['user_id' => $user->id]);
                $generated++;
            } catch (\Exception $e) {
                $errors[] = "{$fungsionaris->nama}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'total' => $total,
            'generated' => $generated,
            'errors' => $errors,
            'message' => "{$generated} akun guru/pegawai berhasil di-generate dari {$total} data."
        ]);
    }

    public function getPendingAccounts()
    {
        $schoolId = auth()->user()->school_id;
        $pending = Fungsionaris::where('school_id', $schoolId)
            ->whereNull('user_id')
            ->select('id', 'nama')
            ->get();
            
        return response()->json($pending);
    }

    public function generateSingleAccount(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:fungsionaris,id'
        ]);

        $fungsionaris = Fungsionaris::findOrFail($request->id);
        
        if ($fungsionaris->user_id) {
            return response()->json(['success' => false, 'message' => 'Akun sudah ada.'], 400);
        }

        try {
            $schoolId = auth()->user()->school_id;
            
            // Generate random number for email
            $randomNumber = mt_rand(100000, 999999);
            $email = $randomNumber . '@guru.literasia.org';
            
            // Make sure email is unique
            while (User::where('email', $email)->exists()) {
                $randomNumber = mt_rand(100000, 999999);
                $email = $randomNumber . '@guru.literasia.org';
            }

            $user = User::create([
                'school_id' => $schoolId,
                'name' => $fungsionaris->nama,
                'username' => (string)$randomNumber,
                'email' => $email,
                'password' => Hash::make('literasia'),
                'role' => $fungsionaris->jabatan,
            ]);

            $fungsionaris->update(['user_id' => $user->id]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function resetPassword($id)
    {
        $fungsionaris = Fungsionaris::findOrFail($id);
        
        if (!$fungsionaris->user_id) {
            return response()->json(['error' => 'Fungsionaris ini belum memiliki akun.'], 404);
        }

        $user = User::find($fungsionaris->user_id);
        if (!$user) {
            return response()->json(['error' => 'Akun user tidak ditemukan.'], 404);
        }

        $user->update([
            'password' => Hash::make('literasia')
        ]);

        return response()->json([
            'success' => true,
            'message' => "Password {$fungsionaris->nama} berhasil direset ke 'literasia'."
        ]);
    }

    public function downloadTemplate()
    {
        return Excel::download(new FungsionarisTemplateExport, 'template_import_fungsionaris.xlsx');
    }

    public function show($id)
    {
        $fungsionaris = Fungsionaris::with('user')->findOrFail($id);
        return response()->json($fungsionaris);
    }

    public function export(Request $request)
    {
        $jabatan = $request->query('jabatan');
        $filename = $jabatan ? "data_{$jabatan}_" : "data_fungsionaris_";
        $filename .= date('Y-m-d') . '.xlsx';
        
        return Excel::download(new FungsionarisExport($jabatan), $filename);
    }
}

