<?php

namespace App\Http\Controllers;

use App\Models\Sambutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SambutanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sambutans = Sambutan::when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                             ->orWhere('konten', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.sambutan.index', compact('sambutans', 'search'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'dinas'])) {
            return response()->json(['error' => 'Akses dilarang.'], 403);
        }
        $request->validate([
            'judul' => 'required|string|max:255',
            'thumbnail' => 'required|image|max:2048',
            'konten' => 'required',
        ]);

        $path = $request->file('thumbnail')->store('sambutans', 'public');

        Sambutan::create([
            'judul' => $request->judul,
            'thumbnail' => $path,
            'konten' => $request->konten,
        ]);

        return response()->json(['success' => 'Sambutan berhasil ditambahkan!']);
    }

    public function show($id)
    {
        return response()->json(Sambutan::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'dinas'])) {
            return response()->json(['error' => 'Akses dilarang.'], 403);
        }
        $sambutan = Sambutan::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'konten' => 'required',
        ]);

        $data = [
            'judul' => $request->judul,
            'konten' => $request->konten,
        ];

        if ($request->hasFile('thumbnail')) {
            if ($sambutan->thumbnail) {
                Storage::disk('public')->delete($sambutan->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('sambutans', 'public');
        }

        $sambutan->update($data);

        return response()->json(['success' => 'Sambutan berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'dinas'])) {
            return response()->json(['error' => 'Akses dilarang.'], 403);
        }
        $sambutan = Sambutan::findOrFail($id);
        if ($sambutan->thumbnail) {
            Storage::disk('public')->delete($sambutan->thumbnail);
        }
        $sambutan->delete();

        return response()->json(['success' => 'Sambutan berhasil dihapus.']);
    }
}
