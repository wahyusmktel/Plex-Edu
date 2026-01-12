<?php

namespace App\Http\Controllers;

use App\Models\MasterPelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\PelanggaranPegawai;
use App\Models\Siswa;
use App\Models\Fungsionaris;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PelanggaranSiswaExport;
use App\Exports\PelanggaranPegawaiExport;

class PelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'pengaturan');
        $search = $request->query('search');

        $masterPelanggarans = MasterPelanggaran::when($search && $tab == 'pengaturan', function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%");
        })->paginate(10, ['*'], 'pengaturan_page')->withQueryString();

        $pelanggaranSiswas = PelanggaranSiswa::with(['siswa', 'masterPelanggaran'])
            ->when($search && $tab == 'siswa', function($q) use ($search) {
                $q->whereHas('siswa', function($sq) use ($search) {
                    $sq->where('nama_lengkap', 'like', "%{$search}%");
                });
            })
            ->paginate(10, ['*'], 'siswa_page')
            ->withQueryString();

        $pelanggaranPegawais = PelanggaranPegawai::with(['fungsionaris', 'masterPelanggaran'])
            ->when($search && $tab == 'pegawai', function($q) use ($search) {
                $q->whereHas('fungsionaris', function($fq) use ($search) {
                    $fq->where('nama', 'like', "%{$search}%");
                });
            })
            ->paginate(10, ['*'], 'pegawai_page')
            ->withQueryString();

        $siswas = Siswa::orderBy('nama_lengkap', 'asc')->get();
        $pegawais = Fungsionaris::orderBy('nama', 'asc')->get();
        $masterSiswa = MasterPelanggaran::where('jenis', 'siswa')->where('status', true)->get();
        $masterPegawai = MasterPelanggaran::where('jenis', 'pegawai')->where('status', true)->get();

        return view('admin.pelanggaran.index', compact(
            'tab', 'search', 'masterPelanggarans', 'pelanggaranSiswas', 
            'pelanggaranPegawais', 'siswas', 'pegawais', 'masterSiswa', 'masterPegawai'
        ));
    }

    // Master Pelanggaran CRUD
    public function storeMaster(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required|in:siswa,pegawai',
            'poin' => 'required|integer',
        ]);

        MasterPelanggaran::create($request->all());
        return response()->json(['success' => 'Master pelanggaran berhasil ditambahkan']);
    }

    public function showMaster($id)
    {
        return response()->json(MasterPelanggaran::findOrFail($id));
    }

    public function updateMaster(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required|in:siswa,pegawai',
            'poin' => 'required|integer',
        ]);

        $master = MasterPelanggaran::findOrFail($id);
        $master->update($request->all());
        return response()->json(['success' => 'Master pelanggaran berhasil diperbarui']);
    }

    public function destroyMaster($id)
    {
        MasterPelanggaran::destroy($id);
        return response()->json(['success' => 'Master pelanggaran berhasil dihapus']);
    }

    // Pelanggaran Siswa CRUD
    public function storeSiswa(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'master_pelanggaran_id' => 'required|exists:master_pelanggarans,id',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable',
            'tindak_lanjut' => 'nullable',
        ]);

        $pelanggaran = PelanggaranSiswa::create($request->all());
        return response()->json([
            'success' => 'Pelanggaran siswa berhasil dicatat',
            'pdf_url' => route('pelanggaran.pdf-siswa', $pelanggaran->id)
        ]);
    }

    public function showSiswa($id)
    {
        return response()->json(PelanggaranSiswa::with(['siswa.kelas', 'masterPelanggaran'])->findOrFail($id));
    }

    public function updateSiswa(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'master_pelanggaran_id' => 'required|exists:master_pelanggarans,id',
            'tanggal' => 'required|date',
        ]);

        $pelanggaran = PelanggaranSiswa::findOrFail($id);
        $pelanggaran->update($request->all());
        return response()->json(['success' => 'Pelanggaran siswa berhasil diperbarui']);
    }

    public function destroySiswa($id)
    {
        PelanggaranSiswa::destroy($id);
        return response()->json(['success' => 'Pelanggaran siswa berhasil dihapus']);
    }

    // Pelanggaran Pegawai CRUD
    public function storePegawai(Request $request)
    {
        $request->validate([
            'fungsionaris_id' => 'required|exists:fungsionaris,id',
            'master_pelanggaran_id' => 'required|exists:master_pelanggarans,id',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable',
            'tindak_lanjut' => 'nullable',
        ]);

        $pelanggaran = PelanggaranPegawai::create($request->all());
        return response()->json([
            'success' => 'Pelanggaran pegawai berhasil dicatat',
            'pdf_url' => route('pelanggaran.pdf-pegawai', $pelanggaran->id)
        ]);
    }

    public function showPegawai($id)
    {
        return response()->json(PelanggaranPegawai::with(['fungsionaris', 'masterPelanggaran'])->findOrFail($id));
    }

    public function updatePegawai(Request $request, $id)
    {
        $request->validate([
            'fungsionaris_id' => 'required|exists:fungsionaris,id',
            'master_pelanggaran_id' => 'required|exists:master_pelanggarans,id',
            'tanggal' => 'required|date',
        ]);

        $pelanggaran = PelanggaranPegawai::findOrFail($id);
        $pelanggaran->update($request->all());
        return response()->json(['success' => 'Pelanggaran pegawai berhasil diperbarui']);
    }

    public function destroyPegawai($id)
    {
        PelanggaranPegawai::destroy($id);
        return response()->json(['success' => 'Pelanggaran pegawai berhasil dihapus']);
    }

    // Report Exports
    public function pdfSiswa($id)
    {
        $data = PelanggaranSiswa::with(['siswa.kelas', 'masterPelanggaran'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.pelanggaran.pdf-siswa', compact('data'));
        return $pdf->stream('laporan-pelanggaran-siswa.pdf');
    }

    public function pdfPegawai($id)
    {
        $data = PelanggaranPegawai::with(['fungsionaris', 'masterPelanggaran'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.pelanggaran.pdf-pegawai', compact('data'));
        return $pdf->stream('laporan-pelanggaran-pegawai.pdf');
    }

    public function exportExcelSiswa()
    {
        return Excel::download(new PelanggaranSiswaExport, 'data-pelanggaran-siswa.xlsx');
    }

    public function exportExcelPegawai()
    {
        return Excel::download(new PelanggaranPegawaiExport, 'data-pelanggaran-pegawai.xlsx');
    }
}
