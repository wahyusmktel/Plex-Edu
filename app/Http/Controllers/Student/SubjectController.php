<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $siswa = $user->siswa;

        if (!$siswa || !$siswa->kelas_id) {
            return back()->with('error', 'Data kelas tidak ditemukan.');
        }

        // Get unique subjects from student's class schedule
        $subjectIds = Schedule::where('kelas_id', $siswa->kelas_id)
            ->distinct()
            ->pluck('subject_id');

        $subjects = Subject::with('guru')
            ->whereIn('id', $subjectIds)
            ->get();

        return view('student.subjects.index', compact('subjects'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $siswa = $user->siswa;

        if (!$siswa || !$siswa->kelas_id) {
            return back()->with('error', 'Data kelas tidak ditemukan.');
        }

        $subject = Subject::with('guru')->findOrFail($id);

        // Fetch schedules for this subject in the student's class
        $schedules = Schedule::with('jam')
            ->where('kelas_id', $siswa->kelas_id)
            ->where('subject_id', $id)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->get()
            ->groupBy('hari');

        return view('student.subjects.show', compact('subject', 'schedules'));
    }
}
