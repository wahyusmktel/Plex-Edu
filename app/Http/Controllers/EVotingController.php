<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionCandidate;
use App\Models\Siswa;
use App\Exports\EVotingExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EVotingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $elections = Election::with(['candidates.student'])
            ->when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        // Data for results tab (including vote counts)
        $results = Election::with(['candidates.student'])
            ->withCount('votes')
            ->get()
            ->map(function ($election) {
                return [
                    'id' => $election->id,
                    'judul' => $election->judul,
                    'total_votes' => $election->votes_count,
                    'chart_data' => $election->candidates->map(function ($c) {
                        return [
                            'label' => $c->student->nama_lengkap,
                            'votes' => $c->votes()->count()
                        ];
                    })
                ];
            });

        $siswas = Siswa::select('id', 'nama_lengkap', 'nisn')->orderBy('nama_lengkap')->get();

        return view('admin.e-voting.index', compact('elections', 'results', 'siswas', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'candidates' => 'required|array|min:2',
            'candidates.*.siswa_id' => 'required|exists:siswas,id',
            'candidates.*.no_urut' => 'required|integer',
        ]);

        DB::transaction(function () use ($request) {
            $election = Election::create($request->only(['judul', 'jenis', 'start_date', 'end_date']));

            foreach ($request->candidates as $candidate) {
                $election->candidates()->create([
                    'siswa_id' => $candidate['siswa_id'],
                    'no_urut' => $candidate['no_urut'],
                ]);
            }
        });

        return response()->json(['success' => 'Pemilihan berhasil dibuat!']);
    }

    public function show($id)
    {
        return response()->json(Election::with('candidates.student')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $election = Election::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'candidates' => 'required|array|min:2',
            'candidates.*.siswa_id' => 'required|exists:siswas,id',
            'candidates.*.no_urut' => 'required|integer',
        ]);

        DB::transaction(function () use ($request, $election) {
            $election->update($request->only(['judul', 'jenis', 'start_date', 'end_date']));

            $election->candidates()->delete();
            foreach ($request->candidates as $candidate) {
                $election->candidates()->create([
                    'siswa_id' => $candidate['siswa_id'],
                    'no_urut' => $candidate['no_urut'],
                ]);
            }
        });

        return response()->json(['success' => 'Pemilihan berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        Election::findOrFail($id)->delete();
        return response()->json(['success' => 'Pemilihan berhasil dihapus.']);
    }

    public function exportExcel($id)
    {
        $election = Election::findOrFail($id);
        return Excel::download(new EVotingExport($id), 'Hasil_Vote_' . str_replace(' ', '_', $election->judul) . '.xlsx');
    }

    public function exportPdf($id)
    {
        $election = Election::with(['candidates.student', 'votes'])->findOrFail($id);
        
        $results = $election->candidates->map(function ($c) {
            return [
                'nama' => $c->student->nama_lengkap,
                'no_urut' => $c->no_urut,
                'total_suara' => $c->votes()->count()
            ];
        });

        $pdf = Pdf::loadView('admin.e-voting.export_pdf', compact('election', 'results'));
        return $pdf->download('Hasil_Vote_' . str_replace(' ', '_', $election->judul) . '.pdf');
    }
}
