<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

/**
 * @group School Management
 * 
 * APIs for managing school identity, settings, departments, and classes.
 */
class SchoolManagementController extends Controller
{
    /**
     * Get School Identity
     * 
     * Get the identity details of the authenticated user's school.
     * 
     * @authenticated
     */
    public function getIdentity(Request $request)
    {
        $school = $request->user()->school;
        
        return response()->json([
            'status' => 'success',
            'data' => $school
        ]);
    }

    /**
     * Update School Identity
     * 
     * Update the identity details of the authenticated user's school.
     * 
     * @authenticated
     * @bodyParam nama_sekolah string required The name of the school.
     * @bodyParam npsn string required The NPSN of the school.
     * @bodyParam alamat string required The full address.
     * @bodyParam status_sekolah string required The status (Negeri/Swasta).
     */
    public function updateIdentity(Request $request)
    {
        $school = $request->user()->school;
        
        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'required|string|max:20',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten_kota' => 'required|string',
            'kecamatan' => 'required|string',
            'desa_kelurahan' => 'required|string',
            'status_sekolah' => 'required|in:Negeri,Swasta',
        ]);

        $school->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Identitas sekolah berhasil diperbarui',
            'data' => $school
        ]);
    }

    /**
     * List School Settings
     * 
     * Get all school settings (academic years and semesters).
     * 
     * @authenticated
     */
    public function getSettings()
    {
        $settings = SchoolSetting::orderBy('tahun_pelajaran', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $settings
        ]);
    }

    /**
     * Create School Setting
     * 
     * Add a new academic year/semester setting.
     * 
     * @authenticated
     * @bodyParam semester string required The semester (Ganjil/Genap).
     * @bodyParam tahun_pelajaran string required The academic year (e.g., 2023/2024).
     * @bodyParam jenjang string required The education level.
     * @bodyParam is_active boolean active status.
     */
    public function storeSetting(Request $request)
    {
        $validated = $request->validate([
            'semester' => 'required|string',
            'tahun_pelajaran' => 'required|string',
            'jenjang' => 'required|string',
            'is_active' => 'boolean',
        ]);

        if ($request->is_active) {
            SchoolSetting::query()->update(['is_active' => false]);
        }

        $setting = SchoolSetting::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengaturan sekolah berhasil ditambahkan',
            'data' => $setting
        ]);
    }

    /**
     * Activate School Setting
     * 
     * Set a specific academic year/semester as the active one.
     * 
     * @authenticated
     */
    public function activateSetting($id)
    {
        SchoolSetting::query()->update(['is_active' => false]);
        $setting = SchoolSetting::findOrFail($id);
        $setting->is_active = true;
        $setting->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Semester berhasil diaktifkan',
            'data' => $setting
        ]);
    }

    /**
     * Delete School Setting
     * 
     * Remove a school setting record.
     * 
     * @authenticated
     */
    public function destroySetting($id)
    {
        $setting = SchoolSetting::findOrFail($id);
        $setting->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pengaturan berhasil dihapus'
        ]);
    }

    /**
     * List Departments (Jurusan)
     * 
     * Get all departments in the school.
     * 
     * @authenticated
     */
    public function getJurusan()
    {
        $jurusans = Jurusan::all();
        return response()->json([
            'status' => 'success',
            'data' => $jurusans
        ]);
    }

    /**
     * Store Department (Jurusan)
     * 
     * Create a new department.
     * 
     * @authenticated
     */
    public function storeJurusan(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $jurusan = Jurusan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Jurusan berhasil ditambahkan',
            'data' => $jurusan
        ]);
    }

    /**
     * Update Department (Jurusan)
     * 
     * Update an existing department.
     * 
     * @authenticated
     */
    public function updateJurusan(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $jurusan->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Jurusan berhasil diperbarui',
            'data' => $jurusan
        ]);
    }

    /**
     * Delete Department (Jurusan)
     * 
     * Remove a department.
     * 
     * @authenticated
     */
    public function destroyJurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jurusan berhasil dihapus'
        ]);
    }

    /**
     * List Classes (Kelas)
     * 
     * Get all classes in the school with relationships.
     * 
     * @authenticated
     */
    public function getKelas()
    {
        $kelas = Kelas::with(['waliKelas', 'jurusan'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $kelas
        ]);
    }

    /**
     * Get Class Detail
     * 
     * Get details of a specific class.
     * 
     * @authenticated
     */
    public function showKelas($id)
    {
        $kelas = Kelas::with(['waliKelas', 'jurusan'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $kelas
        ]);
    }

    /**
     * Store Class (Kelas)
     * 
     * Create a new class.
     * 
     * @authenticated
     */
    public function storeKelas(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|string',
            'wali_kelas_id' => 'nullable|exists:fungsionaris,id',
            'jurusan_id' => 'nullable|exists:jurusans,id',
            'kapasitas' => 'nullable|integer',
            'keterangan' => 'nullable|string',
        ]);

        $kelas = Kelas::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kelas berhasil ditambahkan',
            'data' => $kelas
        ]);
    }

    /**
     * Update Class (Kelas)
     * 
     * Update an existing class.
     * 
     * @authenticated
     */
    public function updateKelas(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|string',
            'wali_kelas_id' => 'nullable|exists:fungsionaris,id',
            'jurusan_id' => 'nullable|exists:jurusans,id',
            'kapasitas' => 'nullable|integer',
            'keterangan' => 'nullable|string',
        ]);

        $kelas->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kelas berhasil diperbarui',
            'data' => $kelas
        ]);
    }

    /**
     * Delete Class (Kelas)
     * 
     * Remove a class.
     * 
     * @authenticated
     */
    public function destroyKelas($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kelas berhasil dihapus'
        ]);
    }

    /**
     * Get Regional Data
     * 
     * Helper to fetch regional data from external API.
     * 
     * @authenticated
     */
    public function getRegionalData($type, $code = null)
    {
        $url = "https://wilayah.id/api/{$type}";
        if ($code) {
            $url .= "/{$code}";
        }
        $url .= ".json";

        try {
            $response = Http::get($url);
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data wilayah'
            ], 500);
        }
    }
}
