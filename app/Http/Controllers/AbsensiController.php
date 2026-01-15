<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Subject;
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
        $selectedSubject = $request->input('subject_id');
        $search = $request->input('search');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Subjects filtered for Guru if applicable
        $subjectsQuery = Subject::with('guru')->orderBy('nama_pelajaran');
        if (auth()->user()->role === 'guru') {
            $guruId = auth()->user()->fungsionaris->id ?? null;
            $subjectsQuery->where('guru_id', $guruId);
            
            // If no subject selected, default to first assigned subject to ensure they only see their data
            if (!$selectedSubject) {
                $firstSubject = (clone $subjectsQuery)->first();
                $selectedSubject = $firstSubject->id ?? null;
            }
        }
        $subjects = $subjectsQuery->get();

        $students = Siswa::when($selectedClass, function ($query, $classId) {
                return $query->where('kelas_id', $classId);
            })
            ->when($search, function ($query, $search) {
                return $query->where('nama_lengkap', 'like', "%{$search}%");
            })
            ->with(['kelas'])
            ->paginate(30);

        $recap = $this->getRecapData($students->pluck('id')->toArray(), $startDate, $endDate, $selectedSubject);

        return view('admin.absensi.index', [
            'classes' => $classes,
            'subjects' => $subjects,
            'selectedClass' => $selectedClass,
            'selectedSubject' => $selectedSubject,
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'recap' => $recap,
            'students' => $students
        ]);
    }

    private function getRecapData($studentIds, $startDate, $endDate, $subjectId = null)
    {
        $counts = Absensi::whereBetween('tanggal', [$startDate, $endDate])
            ->whereIn('siswa_id', $studentIds)
            ->when($subjectId, function ($query, $subjectId) {
                return $query->where('subject_id', $subjectId);
            })
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
        $subjectId = $request->input('subject_id');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $format = $request->input('format', 'excel');

        $kelas = Kelas::findOrFail($classId);
        $subject = $subjectId ? Subject::findOrFail($subjectId) : null;
        $studentIds = Siswa::where('kelas_id', $classId)->pluck('id')->toArray();
        $recap = $this->getRecapData($studentIds, $startDate, $endDate, $subjectId);

        $data = [
            'recap' => $recap,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedClass' => $kelas,
            'selectedSubject' => $subject
        ];

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.absensi.export_pdf', $data);
            return $pdf->download('Rekap_Absensi_' . $kelas->nama . ($subject ? '_' . $subject->nama_pelajaran : '') . '.pdf');
        }

        return Excel::download(new AbsensiExport($data), 'Rekap_Absensi_' . $kelas->nama . ($subject ? '_' . $subject->nama_pelajaran : '') . '.xlsx');
    }

    public function exportAll(Request $request)
    {
        $subjectId = $request->input('subject_id');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $format = $request->input('format', 'excel');

        $subject = $subjectId ? Subject::findOrFail($subjectId) : null;
        $studentIds = Siswa::pluck('id')->toArray();
        $recap = $this->getRecapData($studentIds, $startDate, $endDate, $subjectId);

        $data = [
            'recap' => $recap,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedClass' => null,
            'selectedSubject' => $subject
        ];

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.absensi.export_pdf', $data);
            return $pdf->download('Rekap_Absensi_Seluruh_Siswa' . ($subject ? '_' . $subject->nama_pelajaran : '') . '.pdf');
        }

        return Excel::download(new AbsensiExport($data), 'Rekap_Absensi_Seluruh_Siswa' . ($subject ? '_' . $subject->nama_pelajaran : '') . '.xlsx');
    }

    public function exportStudent($id, Request $request)
    {
        $subjectId = $request->input('subject_id');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $format = $request->input('format', 'pdf');

        $siswa = Siswa::with('kelas')->findOrFail($id);
        $subject = $subjectId ? Subject::findOrFail($subjectId) : null;
        
        $history = Absensi::where('siswa_id', $id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($subjectId, function ($query, $subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->with('subject')
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
            'endDate' => $endDate,
            'selectedSubject' => $subject
        ];

        if ($format === 'excel') {
             return Excel::download(new AbsensiExport($data + ['view' => 'admin.absensi.export_student_excel']), 'Absensi_' . $siswa->nama_lengkap . ($subject ? '_' . $subject->nama_pelajaran : '') . '.xlsx');
        }

        $pdf = Pdf::loadView('admin.absensi.export_student_pdf', $data);
        return $pdf->download('Absensi_' . $siswa->nama_lengkap . ($subject ? '_' . $subject->nama_pelajaran : '') . '.pdf');
    }
}
