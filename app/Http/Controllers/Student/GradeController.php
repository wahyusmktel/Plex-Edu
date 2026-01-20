<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\ELearningSubmission;
use App\Models\CbtSession;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $siswa = $user->siswa;

        if (!$siswa || !$siswa->kelas_id) {
            return back()->with('error', 'Data kelas tidak ditemukan.');
        }

        // Get subjects for the student's class
        $subjectIds = Schedule::where('kelas_id', $siswa->kelas_id)
            ->distinct()
            ->pluck('subject_id');

        $subjects = Subject::with('guru')
            ->whereIn('id', $subjectIds)
            ->get();

        $grades = $subjects->map(function ($subject) use ($siswa) {
            // Fetch E-Learning Submissions (Assignments)
            $assignments = ELearningSubmission::where('siswa_id', $siswa->id)
                ->whereHas('module.chapter.course', function ($query) use ($subject) {
                    $query->where('subject_id', $subject->id);
                })
                ->get();

            // Fetch CBT Sessions (Exams)
            $exams = CbtSession::where('siswa_id', $siswa->id)
                ->whereHas('cbt', function ($query) use ($subject) {
                    $query->where('subject_id', $subject->id);
                })
                ->get();

            return [
                'subject' => $subject,
                'assignments' => $assignments,
                'exams' => $exams,
                'avg_assignment' => $assignments->avg('score') ?? 0,
                'avg_exam' => $exams->avg('skor') ?? 0,
            ];
        });

        // Calculate overall stats
        $totalAssignments = $grades->sum(fn($g) => $g['assignments']->count());
        $totalExams = $grades->sum(fn($g) => $g['exams']->count());
        $overallAvg = $grades->isNotEmpty() 
            ? ($grades->avg('avg_assignment') + $grades->avg('avg_exam')) / 2 
            : 0;

        return view('student.grades.index', compact('grades', 'totalAssignments', 'totalExams', 'overallAvg'));
    }
}
