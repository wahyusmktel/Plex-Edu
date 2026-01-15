<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $pengumumans = Pengumuman::whereHas('user')
            ->with('user')
            ->when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                             ->orWhere('pesan', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(9);

        return view('admin.pengumuman.index', compact('pengumumans', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required',
            'tanggal_terbit' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_terbit',
            'is_permanen' => 'required|boolean',
        ]);

        Pengumuman::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'pesan' => $request->pesan,
            'tanggal_terbit' => $request->tanggal_terbit,
            'tanggal_berakhir' => $request->is_permanen ? null : $request->tanggal_berakhir,
            'is_permanen' => $request->is_permanen,
        ]);

        return response()->json(['success' => 'Pengumuman berhasil diterbitkan!']);
    }

    public function show($id)
    {
        return response()->json(Pengumuman::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required',
            'tanggal_terbit' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_terbit',
            'is_permanen' => 'required|boolean',
        ]);

        $pengumuman->update([
            'judul' => $request->judul,
            'pesan' => $request->pesan,
            'tanggal_terbit' => $request->tanggal_terbit,
            'tanggal_berakhir' => $request->is_permanen ? null : $request->tanggal_berakhir,
            'is_permanen' => $request->is_permanen,
        ]);

        return response()->json(['success' => 'Pengumuman berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return response()->json(['success' => 'Pengumuman telah dihapus.']);
    }
}
