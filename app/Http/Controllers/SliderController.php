<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sliders = Slider::when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                             ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12);

        return view('admin.slider.index', compact('sliders', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'required|image|max:2048',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'nullable|date|after_or_equal:waktu_mulai',
            'is_permanen' => 'required|boolean',
            'link' => 'nullable|url',
        ]);

        $path = $request->file('gambar')->store('sliders', 'public');

        Slider::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $path,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->is_permanen ? null : $request->waktu_selesai,
            'is_permanen' => $request->is_permanen,
            'link' => $request->link,
        ]);

        return response()->json(['success' => 'Slider berhasil ditambahkan!']);
    }

    public function show($id)
    {
        return response()->json(Slider::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'nullable|date|after_or_equal:waktu_mulai',
            'is_permanen' => 'required|boolean',
            'link' => 'nullable|url',
        ]);

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->is_permanen ? null : $request->waktu_selesai,
            'is_permanen' => $request->is_permanen,
            'link' => $request->link,
        ];

        if ($request->hasFile('gambar')) {
            if ($slider->gambar) {
                Storage::disk('public')->delete($slider->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('sliders', 'public');
        }

        $slider->update($data);

        return response()->json(['success' => 'Slider berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->gambar) {
            Storage::disk('public')->delete($slider->gambar);
        }
        $slider->delete();

        return response()->json(['success' => 'Slider berhasil dihapus.']);
    }
}
