<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Exports\AbsensiExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $classes = Kelas::orderBy('nama')->get();
        $selectedClass = $request->input('kelas_id');
        $search = $request->input('search');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $students = Siswa::when($selectedClass, function ($query, $classId) {
                return $query->where('kelas_id', $classId);
            })
            ->when($search, function ($query, $search) {
                return $query->where('nama_lengkap', 'like', "%{$search}%");
            })
            ->with(['kelas'])
            ->paginate(30);

        $recap = $this->getRecapData($students->pluck('id')->toArray(), $startDate, $endDate);

        return view('admin.absensi.index', [
            'classes' => $classes,
            'selectedClass' => $selectedClass,
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'recap' => $recap,
            'students' => $students
        ]);
    }

    private function getRecapData($studentIds, $startDate, $endDate)
    {
        $counts = Absensi::whereBetween('tanggal', [$startDate, $endDate])
            ->whereIn('siswa_id', $studentIds)
            ->select('siswa_id', 'status', DB::raw('count(*) as total'))
            ->groupBy('siswa_id', 'status')
            ->get()
            ->groupBy('siswa_id');

        $students = Siswa::whereIn('id', $studentIds)->with('kelas')->get();

        return $students->map(function ($student) use ($counts) {
            $sCounts = $counts->get($student->id, collect());
            return [
                'id' => $student->id,
                'nama' => $student->nama_lengkap,
                'kelas' => $student->kelas->nama ?? '-',
                'H' => $sCounts->where('status', 'H')->first()->total ?? 0,
                'A' => $sCounts->where('status', 'A')->first()->total ?? 0,
                'S' => $sCounts->where('status', 'S')->first()->total ?? 0,
                'I' => $sCounts->where('status', 'I')->first()->total ?? 0,
            ];
        });
    }

    public function exportClass(Request $request)
    {
        $classId = $request->input('kelas_id');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $format = $request->input('format', 'excel');

        $kelas = Kelas::findOrFail($classId);
        $studentIds = Siswa::where('kelas_id', $classId)->pluck('id')->toArray();
        $recap = $this->getRecapData($studentIds, $startDate, $endDate);

        $data = [
            'recap' => $recap,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedClass' => $kelas
        ];

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.absensi.export_pdf', $data);
            return $pdf->download('Rekap_Absensi_' . $kelas->nama . '.pdf');
        }

        return Excel::download(new AbsensiExport($data), 'Rekap_Absensi_' . $kelas->nama . '.xlsx');
    }

    public function exportAll(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $format = $request->input('format', 'excel');

        $studentIds = Siswa::pluck('id')->toArray();
        $recap = $this->getRecapData($studentIds, $startDate, $endDate);

        $data = [
            'recap' => $recap,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedClass' => null
        ];

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.absensi.export_pdf', $data);
            return $pdf->download('Rekap_Absensi_Seluruh_Siswa.pdf');
        }

        return Excel::download(new AbsensiExport($data), 'Rekap_Absensi_Seluruh_Siswa.xlsx');
    }

    public function exportStudent($id, Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $format = $request->input('format', 'pdf');

        $siswa = Siswa::with('kelas')->findOrFail($id);
        $history = Absensi::where('siswa_id', $id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        $recap = [
            'H' => $history->where('status', 'H')->count(),
            'A' => $history->where('status', 'A')->count(),
            'S' => $history->where('status', 'S')->count(),
            'I' => $history->where('status', 'I')->count(),
        ];

        $data = [
            'siswa' => $siswa,
            'history' => $history,
            'recap' => $recap,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        if ($format === 'excel') {
             // For student individual, we might want a different view but for now let's reuse or just PDF which is more common for individual
             return Excel::download(new AbsensiExport($data), 'Absensi_' . $siswa->nama_lengkap . '.xlsx');
        }

        $pdf = Pdf::loadView('admin.absensi.export_student_pdf', $data);
        return $pdf->download('Absensi_' . $siswa->nama_lengkap . '.pdf');
    }
}
