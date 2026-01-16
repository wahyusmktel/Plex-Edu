<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 * 
 * APIs for managing user authentication
 */
class AuthController extends Controller
{
    /**
     * Login User
     * 
     * Authenticate user with username/email and password to get an API token.
     * 
     * @bodyParam login string required Username or Email of the user. Example: admin@literasia.com
     * @bodyParam password string required Password of the user. Example: password
     * @bodyParam device_name string required Name of the device. Example: Android Phone
     * 
     * @response {
     *  "status": "success",
     *  "message": "Login successful",
     *  "data": {
     *    "token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz",
     *    "user": {
     *      "id": "...",
     *      "name": "Admin User",
     *      "username": "admin",
     *      "role": "admin"
     *    }
     *  }
     * }
     * 
     * @response 422 {
     *  "message": "The given data was invalid.",
     *  "errors": {
     *    "login": ["Invalid credentials"]
     *  }
     * }
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        if ($user->school_id) {
            $user->load('school');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role,
                    'school_id' => $user->school_id,
                    'school_name' => $user->school?->nama_sekolah
                ]
            ]
        ]);
    }

    /**
     * Logout User
     * 
     * Revoke the current API token.
     * 
     * @authenticated
     * 
     * @response {
     *  "status": "success",
     *  "message": "Logged out successfully"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }
}
