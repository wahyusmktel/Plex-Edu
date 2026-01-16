<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\LibraryItem;
use Illuminate\Http\Request;

/**
 * @group Dashboard
 * 
 * APIs for retrieving dashboard statistics
 */
class DashboardController extends Controller
{
    /**
     * Get Dashboard Statistics
     * 
     * Retrieve statistics based on the authenticated user's role.
     * 
     * @authenticated
     * 
     * @response {
     *  "status": "success",
     *  "data": {
     *    "role": "admin",
     *    "stats": {
     *      "total_siswa": 100,
     *      "total_guru": 10,
     *      "total_kelas": 5,
     *      "total_buku": 50
     *    }
     *  }
     * }
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'dinas') {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'role' => 'dinas',
                    'stats' => [
                        'total_sekolah' => School::count(),
                        'menunggu_persetujuan' => School::where('status', 'pending')->count(),
                        'sekolah_aktif' => School::where('status', 'approved')->where('is_active', true)->count(),
                        'total_siswa_nasional' => Siswa::withoutGlobalScopes()->count(),
                    ]
                ]
            ]);
        }

        // Default: School-specific stats (scoped via BelongsToSchool trait/global scopes)
        return response()->json([
            'status' => 'success',
            'data' => [
                'role' => $user->role,
                'stats' => [
                    'total_siswa' => Siswa::count(),
                    'total_guru' => User::where('role', 'guru')->count(),
                    'total_kelas' => Kelas::count(),
                    'total_buku' => LibraryItem::where('category', 'book')->count(),
                ]
            ]
        ]);
    }
}
