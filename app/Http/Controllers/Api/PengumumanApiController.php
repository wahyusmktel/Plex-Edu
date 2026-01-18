<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = now();

        // Get announcements from student's school AND from dinas
        $pengumumans = Pengumuman::withoutGlobalScope('school')
            ->with(['user' => function($q) {
                $q->withoutGlobalScope('school');
            }])
            ->where(function ($query) use ($user) {
                // Announcements from student's own school
                $query->where('school_id', $user->school_id)
                    // OR announcements created by dinas users (for all schools)
                    ->orWhereHas('user', function ($q) {
                        $q->where('role', 'dinas');
                    });
            })
            ->where(function ($query) use ($now) {
                // Active announcements: published, and either permanent or not expired
                $query->where('tanggal_terbit', '<=', $now->toDateString())
                    ->where(function ($q) use ($now) {
                        $q->where('is_permanen', true)
                          ->orWhere('tanggal_berakhir', '>=', $now->toDateString());
                    });
            })
            ->orderBy('tanggal_terbit', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'judul' => $p->judul,
                    'pesan' => $p->pesan,
                    'tanggal_terbit' => \Carbon\Carbon::parse($p->tanggal_terbit)->format('d M Y'),
                    'is_permanen' => $p->is_permanen,
                    'penulis' => $p->user?->name ?? 'Admin',
                    'source' => $p->user?->role === 'dinas' ? 'dinas' : 'sekolah',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $pengumumans
        ]);
    }
}
