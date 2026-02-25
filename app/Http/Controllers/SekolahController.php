<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\SchoolSetting;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Fungsionaris;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class SekolahController extends Controller
{
    public function index()
    {
        $allSettings = SchoolSetting::orderBy('tahun_pelajaran', 'desc')->orderBy('semester', 'desc')->get();
        $settings = $allSettings->where('is_active', true)->first() ?? $allSettings->first();
        $jurusans = Jurusan::all();
        $kelas = Kelas::with(['waliKelas', 'jurusan'])->withCount('siswas')->get();
        $gurus = Fungsionaris::where('jabatan', 'guru')->get();
        $identity = auth()->user()->school;

        return view('admin.sekolah.index', compact('settings', 'allSettings', 'jurusans', 'kelas', 'gurus', 'identity'));
    }

    public function updateIdentity(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required',
            'status_sekolah' => 'required|in:Negeri,Swasta',
            'jenjang' => 'required|in:sd,smp,sma_smk',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $identity = auth()->user()->school;
        if ($identity) {
            $identity->update($request->all());
        }

        return response()->json(['success' => 'Identitas sekolah berhasil diperbarui', 'data' => $identity]);
    }

    public function getRegionalData($type, $code = null)
    {
        $url = "https://wilayah.id/api/{$type}";
        if ($code) {
            $url .= "/{$code}";
        }
        $url .= ".json";

        try {
            $response = Http::get($url);
            return $response->json();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data wilayah'], 500);
        }
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'semester' => 'required',
            'tahun_pelajaran' => 'required',
            'jenjang' => 'required',
        ]);

        // If this is set to active, deactivate others
        if ($request->is_active) {
            SchoolSetting::query()->update(['is_active' => false]);
        }

        $settings = SchoolSetting::create([
            'semester' => $request->semester,
            'tahun_pelajaran' => $request->tahun_pelajaran,
            'jenjang' => $request->jenjang,
            'is_active' => $request->is_active ? true : false,
        ]);

        return response()->json(['success' => 'Pengaturan sekolah berhasil ditambahkan', 'data' => $settings]);
    }

    public function activateSettings($id)
    {
        SchoolSetting::query()->update(['is_active' => false]);
        $settings = SchoolSetting::findOrFail($id);
        $settings->is_active = true;
        $settings->save();

        return response()->json(['success' => 'Semester berhasil diaktifkan']);
    }

    public function destroySettings($id)
    {
        SchoolSetting::destroy($id);
        return response()->json(['success' => 'Pengaturan berhasil dihapus']);
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
        return response()->json(Kelas::withCount('siswas')->findOrFail($id));
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

    // Class Member Mapping API
    public function getKelasMembers($id)
    {
        $members = Siswa::where('kelas_id', $id)
            ->orderBy('nama_lengkap', 'asc')
            ->get();
            
        return response()->json($members);
    }

    public function getUnassignedStudents()
    {
        $school_id = auth()->user()->school->id;
        $unassigned = Siswa::where('school_id', $school_id)
            ->whereNull('kelas_id')
            ->orderBy('nama_lengkap', 'asc')
            ->get();
            
        return response()->json($unassigned);
    }

    public function addStudentToKelas(Request $request, $id)
    {
        $request->validate(['siswa_id' => 'required']);
        
        $siswa = Siswa::findOrFail($request->siswa_id);
        
        if ($siswa->school_id !== auth()->user()->school->id) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $siswa->kelas_id = $id;
        $siswa->save();

        return response()->json(['success' => 'Siswa berhasil ditambahkan ke kelas']);
    }

    public function removeStudentFromKelas(Request $request, $id)
    {
        $request->validate(['siswa_id' => 'required']);
        
        $siswa = Siswa::findOrFail($request->siswa_id);

        if ($siswa->school_id !== auth()->user()->school->id) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $siswa->kelas_id = null;
        $siswa->save();

        return response()->json(['success' => 'Siswa berhasil dihapus dari kelas']);
    }
}
