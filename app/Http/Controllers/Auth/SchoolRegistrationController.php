<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SchoolRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register-school');
    }

    public function register(Request $request)
    {
        $request->validate([
            // Step 1: School Identity
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'required|string|max:20',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten_kota' => 'required|string',
            'kecamatan' => 'required|string',
            'desa_kelurahan' => 'required|string',
            'status_sekolah' => 'required|in:Negeri,Swasta',
            
            // Step 2: Admin Info
            'admin_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // 1. Create School (Status Pending)
            $school = School::create([
                'nama_sekolah' => $request->nama_sekolah,
                'npsn' => $request->npsn,
                'alamat' => $request->alamat,
                'provinsi' => $request->provinsi,
                'kabupaten_kota' => $request->kabupaten_kota,
                'kecamatan' => $request->kecamatan,
                'desa_kelurahan' => $request->desa_kelurahan,
                'status_sekolah' => $request->status_sekolah,
                'status' => 'pending',
                'is_active' => false,
            ]);

            // 2. Create School Admin User
            User::create([
                'school_id' => $school->id,
                'name' => $request->admin_name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'admin',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil! Akun anda sedang menunggu persetujuan dari Admin Dinas Pendidikan.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat pendaftaran. Silahkan coba lagi.'
            ], 500);
        }
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
            return response()->json(['error' => 'Failed to fetch regional data'], 500);
        }
    }
}
