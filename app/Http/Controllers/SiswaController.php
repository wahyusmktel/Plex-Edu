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
    public function index()
    {
        $siswas = Siswa::with(['user', 'kelas'])->get();
        $kelas = Kelas::all();
        return view('admin.siswa.index', compact('siswas', 'kelas'));
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
                'email' => $request->username . '@siswa.literasia.com',
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'kelas_id' => $request->kelas_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_hp' => $request->no_hp,
                'no_hp_ortu' => $request->no_hp_ortu,
                'sekolah_asal' => $request->sekolah_asal,
            ]);
        });

        return response()->json(['success' => 'Data siswa berhasil disimpan']);
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        
        $request->validate([
            'nama_lengkap' => 'required',
            'nis' => 'required|unique:siswas,nis,' . $id . ',id',
            'nisn' => 'required|unique:siswas,nisn,' . $id . ',id',
            'username' => 'required|unique:users,username,' . $siswa->user_id . ',id',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        DB::transaction(function () use ($request, $siswa) {
            $user = User::findOrFail($siswa->user_id);
            $user->update([
                'name' => $request->nama_lengkap,
                'username' => $request->username,
                'email' => $request->username . '@siswa.literasia.com',
            ]);

            if ($request->password) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $siswa->update([
                'kelas_id' => $request->kelas_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_hp' => $request->no_hp,
                'no_hp_ortu' => $request->no_hp_ortu,
                'sekolah_asal' => $request->sekolah_asal,
            ]);
        });

        return response()->json(['success' => 'Data siswa berhasil diupdate']);
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

        Excel::import(new SiswaImport, $request->file('file'));

        return response()->json(['success' => 'Data siswa berhasil diimport']);
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
