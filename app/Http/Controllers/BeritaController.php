<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\GeneralNotification;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $beritas = Berita::with('user')
            ->when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->orderBy('tanggal_terbit', 'desc')
            ->orderBy('jam_terbit', 'desc')
            ->paginate(10);

        return view('admin.berita.index', compact('beritas', 'search'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'dinas'])) {
            return response()->json(['error' => 'Akses dilarang.'], 403);
        }
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required',
            'thumbnail' => 'nullable|image|max:2048',
            'tanggal_terbit' => 'required|date',
            'jam_terbit' => 'required',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'tanggal_terbit', 'jam_terbit']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $berita = Berita::create($data);

        // Notify all students in this school
        $schoolId = Auth::user()->school_id;
        $students = User::where('role', 'siswa')
            ->where('school_id', $schoolId)
            ->get();

        foreach ($students as $student) {
            $student->notify(new GeneralNotification([
                'type' => 'news',
                'title' => 'Berita Terbaru',
                'message' => $berita->judul,
                'action_type' => 'news_detail',
                'action_id' => $berita->id
            ]));
        }

        return response()->json(['success' => 'Berita berhasil ditambahkan']);
    }

    public function show($id)
    {
        return response()->json(Berita::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'dinas'])) {
            return response()->json(['error' => 'Akses dilarang.'], 403);
        }
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required',
            'thumbnail' => 'nullable|image|max:2048',
            'tanggal_terbit' => 'required|date',
            'jam_terbit' => 'required',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'tanggal_terbit', 'jam_terbit']);

        if ($request->hasFile('thumbnail')) {
            if ($berita->thumbnail) {
                Storage::disk('public')->delete($berita->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $berita->update($data);

        return response()->json(['success' => 'Berita berhasil diperbarui']);
    }

    public function destroy($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'dinas'])) {
            return response()->json(['error' => 'Akses dilarang.'], 403);
        }
        $berita = Berita::findOrFail($id);
        if ($berita->thumbnail) {
            Storage::disk('public')->delete($berita->thumbnail);
        }
        $berita->delete();

        return response()->json(['success' => 'Berita berhasil dihapus']);
    }
}
