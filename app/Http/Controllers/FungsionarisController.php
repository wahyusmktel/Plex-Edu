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

class FungsionarisController extends Controller
{
    public function index()
    {
        $guru = Fungsionaris::where('jabatan', 'guru')->with('user')->get();
        $pegawai = Fungsionaris::where('jabatan', 'pegawai')->with('user')->get();
        return view('admin.fungsionaris.index', compact('guru', 'pegawai'));
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
                'email' => $request->username . '@literasia.com',
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
                'email' => $request->username . '@literasia.com',
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

        Excel::import(new FungsionarisImport, $request->file('file'));

        return response()->json(['success' => 'Data berhasil diimport']);
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
}
