<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        Berita::create($data);

        return response()->json(['success' => 'Berita berhasil ditambahkan']);
    }

    public function show($id)
    {
        return response()->json(Berita::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
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
        $berita = Berita::findOrFail($id);
        if ($berita->thumbnail) {
            Storage::disk('public')->delete($berita->thumbnail);
        }
        $berita->delete();

        return response()->json(['success' => 'Berita berhasil dihapus']);
    }
}
