<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Fungsionaris;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SekolahController extends Controller
{
    public function index()
    {
        $settings = SchoolSetting::first();
        $jurusans = Jurusan::all();
        $kelas = Kelas::with(['waliKelas', 'jurusan'])->get();
        $gurus = Fungsionaris::where('jabatan', 'guru')->get();

        return view('admin.sekolah.index', compact('settings', 'jurusans', 'kelas', 'gurus'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'semester' => 'required',
            'tahun_pelajaran' => 'required',
            'jenjang' => 'required',
        ]);

        $settings = SchoolSetting::first();
        if (!$settings) {
            $settings = new SchoolSetting();
        }

        $settings->semester = $request->semester;
        $settings->tahun_pelajaran = $request->tahun_pelajaran;
        $settings->jenjang = $request->jenjang;
        $settings->save();

        return response()->json(['success' => 'Pengaturan sekolah berhasil diperbarui']);
    }

    // Jurusan CRUD
    public function storeJurusan(Request $request)
    {
        $request->validate(['nama' => 'required']);
        Jurusan::create($request->all());
        return response()->json(['success' => 'Jurusan berhasil ditambahkan']);
    }

    public function showJurusan($id)
    {
        return response()->json(Jurusan::findOrFail($id));
    }

    public function updateJurusan(Request $request, $id)
    {
        $request->validate(['nama' => 'required']);
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update($request->all());
        return response()->json(['success' => 'Jurusan berhasil diperbarui']);
    }

    public function destroyJurusan($id)
    {
        Jurusan::destroy($id);
        return response()->json(['success' => 'Jurusan berhasil dihapus']);
    }

    // Kelas CRUD
    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tingkat' => 'required',
        ]);
        Kelas::create($request->all());
        return response()->json(['success' => 'Kelas berhasil ditambahkan']);
    }

    public function showKelas($id)
    {
        return response()->json(Kelas::findOrFail($id));
    }

    public function updateKelas(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'tingkat' => 'required',
        ]);
        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());
        return response()->json(['success' => 'Kelas berhasil diperbarui']);
    }

    public function destroyKelas($id)
    {
        Kelas::destroy($id);
        return response()->json(['success' => 'Kelas berhasil dihapus']);
    }
}
