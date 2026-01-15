<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * @group School Management
 * 
 * APIs for school registration
 */
class SchoolRegistrationController extends Controller
{
    /**
     * Register New School
     * 
     * Submit a registration request for a new school. This will create a school with 'pending' status and an admin user.
     * 
     * @bodyParam nama_sekolah string required The name of the school. Example: SMA Negeri 1 Jakarta
     * @bodyParam npsn string required The NPSN of the school. Example: 12345678
     * @bodyParam alamat string required The address of the school. Example: Jl. Merdeka No. 1
     * @bodyParam provinsi string required The province of the school. Example: DKI Jakarta
     * @bodyParam kabupaten_kota string required The city/district. Example: Jakarta Pusat
     * @bodyParam kecamatan string required The sub-district. Example: Gambir
     * @bodyParam desa_kelurahan string required The village/ward. Example: Gambir
     * @bodyParam status_sekolah string required The school status (Negeri/Swasta). Example: Negeri
     * @bodyParam admin_name string required The full name of the school admin. Example: Budi Santoso
     * @bodyParam email string required The email for the admin login. Example: budi@sekolah.com
     * @bodyParam username string required The username for the admin login. Example: budiadmin
     * @bodyParam password string required The password for the admin login (min 8 chars). Example: password123
     * @bodyParam password_confirmation string required The password confirmation. Example: password123
     * 
     * @response {
     *  "status": "success",
     *  "message": "Registration successful! Your account is waiting for approval from the Education Office.",
     *  "data": {
     *    "school_id": "...",
     *    "admin_id": "..."
     *  }
     * }
     * 
     * @response 422 {
     *  "message": "The given data was invalid.",
     *  "errors": {
     *    "email": ["The email has already been taken."]
     *  }
     * }
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'required|string|max:20',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten_kota' => 'required|string',
            'kecamatan' => 'required|string',
            'desa_kelurahan' => 'required|string',
            'status_sekolah' => 'required|in:Negeri,Swasta',
            'admin_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

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

            $user = User::create([
                'school_id' => $school->id,
                'name' => $request->admin_name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'admin',
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran berhasil! Akun anda sedang menunggu persetujuan dari Admin Dinas Pendidikan.',
                'data' => [
                    'school_id' => $school->id,
                    'admin_id' => $user->id
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat pendaftaran. Silahkan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
