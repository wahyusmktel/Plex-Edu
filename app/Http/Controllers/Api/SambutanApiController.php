<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sambutan;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class SambutanApiController extends Controller
{
    public function index()
    {
        $sambutans = Sambutan::latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'thumbnail_url' => $item->thumbnail ? asset('storage/' . $item->thumbnail) : null,
                    'konten' => $item->konten,
                    'created_at' => $item->created_at->format('d M Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $sambutans
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'dinas') {
            return response()->json(['success' => false, 'message' => 'Hanya Dinas yang dapat membuat sambutan.'], 403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'thumbnail' => 'required|image|max:2048',
            'konten' => 'required',
        ]);

        $path = $request->file('thumbnail')->store('sambutans', 'public');

        $sambutan = Sambutan::create([
            'judul' => $request->judul,
            'thumbnail' => $path,
            'konten' => $request->konten,
            'school_id' => auth()->user()->school_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sambutan berhasil ditambahkan!',
            'data' => $sambutan
        ]);
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'dinas') {
            return response()->json(['success' => false, 'message' => 'Hanya Dinas yang dapat menghapus sambutan.'], 403);
        }

        $sambutan = Sambutan::findOrFail($id);
        
        if ($sambutan->thumbnail) {
            Storage::disk('public')->delete($sambutan->thumbnail);
        }
        
        $sambutan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sambutan berhasil dihapus.'
        ]);
    }
}
