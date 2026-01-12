<?php

namespace App\Http\Controllers;

use App\Models\ERaport;
use App\Models\Siswa;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ERaportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $raports = ERaport::with('siswa.kelas')
            ->when($search, function($query) use ($search) {
                $query->whereHas('siswa', function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $siswas = Siswa::with('kelas')->orderBy('nama_lengkap', 'asc')->get();
        $activeSetting = SchoolSetting::where('is_active', true)->first();
        
        return view('admin.e-raport.index', compact('raports', 'siswas', 'activeSetting', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'semester' => 'required',
            'tahun_pelajaran' => 'required',
            'file_raport' => 'required|file|mimes:jpg,jpeg,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('file_raport');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('e-raport', $fileName, 'public');

            ERaport::create([
                'siswa_id' => $request->siswa_id,
                'semester' => $request->semester,
                'tahun_pelajaran' => $request->tahun_pelajaran,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
            ]);

            DB::commit();
            return response()->json(['success' => 'Arsip raport berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan arsip: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $raport = ERaport::with('siswa')->findOrFail($id);
        return response()->json($raport);
    }

    public function update(Request $request, $id)
    {
        $raport = ERaport::findOrFail($id);

        $rules = [
            'siswa_id' => 'required|exists:siswas,id',
            'semester' => 'required',
            'tahun_pelajaran' => 'required',
        ];

        if ($request->hasFile('file_raport')) {
            $rules['file_raport'] = 'file|mimes:jpg,jpeg,pdf|max:2048';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $data = [
                'siswa_id' => $request->siswa_id,
                'semester' => $request->semester,
                'tahun_pelajaran' => $request->tahun_pelajaran,
            ];

            if ($request->hasFile('file_raport')) {
                // Delete old file
                if (Storage::disk('public')->exists($raport->file_path)) {
                    Storage::disk('public')->delete($raport->file_path);
                }

                $file = $request->file('file_raport');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('e-raport', $fileName, 'public');

                $data['file_name'] = $file->getClientOriginalName();
                $data['file_path'] = $path;
            }

            $raport->update($data);

            DB::commit();
            return response()->json(['success' => 'Arsip raport berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal memperbarui arsip: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $raport = ERaport::findOrFail($id);

            if (Storage::disk('public')->exists($raport->file_path)) {
                Storage::disk('public')->delete($raport->file_path);
            }

            $raport->delete();
            return response()->json(['success' => 'Arsip raport berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus arsip: ' . $e->getMessage()], 500);
        }
    }
}
