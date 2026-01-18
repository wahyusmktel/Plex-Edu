<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeritaApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get news from student's school AND news from dinas (school_id = null or from dinas users)
        $beritas = Berita::withoutGlobalScope('school')
            ->with(['user' => function($q) {
                $q->withoutGlobalScope('school');
            }])
            ->where(function ($query) use ($user) {
                // News from student's own school
                $query->where('school_id', $user->school_id)
                    // OR news created by dinas users (for all schools)
                    ->orWhereHas('user', function ($q) {
                        $q->where('role', 'dinas');
                    });
            })
            ->where('tanggal_terbit', '<=', now()->toDateString())
            ->orderBy('tanggal_terbit', 'desc')
            ->orderBy('jam_terbit', 'desc')
            ->get()
            ->map(function ($berita) {
                return [
                    'id' => $berita->id,
                    'judul' => $berita->judul,
                    'deskripsi' => $berita->deskripsi,
                    'thumbnail' => $berita->thumbnail ? asset('storage/' . $berita->thumbnail) : null,
                    'tanggal_terbit' => \Carbon\Carbon::parse($berita->tanggal_terbit)->format('d M Y'),
                    'jam_terbit' => $berita->jam_terbit,
                    'penulis' => $berita->user?->name ?? 'Admin',
                    'source' => $berita->user?->role === 'dinas' ? 'dinas' : 'sekolah',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $beritas
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();

        $berita = Berita::withoutGlobalScope('school')
            ->with(['user' => function($q) {
                $q->withoutGlobalScope('school');
            }])
            ->where(function ($query) use ($user) {
                $query->where('school_id', $user->school_id)
                    ->orWhereHas('user', function ($q) {
                        $q->where('role', 'dinas');
                    });
            })
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $berita->id,
                'judul' => $berita->judul,
                'deskripsi' => $berita->deskripsi,
                'thumbnail' => $berita->thumbnail ? asset('storage/' . $berita->thumbnail) : null,
                'tanggal_terbit' => \Carbon\Carbon::parse($berita->tanggal_terbit)->format('d M Y'),
                'jam_terbit' => $berita->jam_terbit,
                'penulis' => $berita->user?->name ?? 'Admin',
                'source' => $berita->user?->role === 'dinas' ? 'dinas' : 'sekolah',
            ]
        ]);
    }
}
