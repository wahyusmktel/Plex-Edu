<?php

namespace App\Http\Controllers;

use App\Models\ELearning;
use App\Models\ELearningChapter;
use App\Models\ELearningModule;
use App\Models\ELearningProgress;
use App\Models\CbtSession;
use App\Models\Subject;
use App\Models\Cbt;
use App\Models\Schedule;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ELearningController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $elearnings = collect();

        if ($user->role === 'siswa') {
            $siswa = $user->siswa;
            if (!$siswa) {
                return redirect()->route('dashboard')->with('error', 'Profil Siswa tidak ditemukan.');
            }

            $subjectIds = Schedule::where('kelas_id', $siswa->kelas_id)
                ->pluck('subject_id')
                ->unique();

            $elearnings = ELearning::with(['subject', 'chapters.modules'])
                ->whereIn('subject_id', $subjectIds)
                ->get()
                ->map(function ($course) use ($siswa) {
                    $totalModules = $course->chapters->flatMap->modules->count();
                    if ($totalModules === 0) {
                        $course->progress_percentage = 0;
                        return $course;
                    }

                    $completedModuleIds = ELearningProgress::where('siswa_id', $siswa->id)
                        ->whereIn('module_id', $course->chapters->flatMap->modules->pluck('id'))
                        ->pluck('module_id')
                        ->toArray();

                    $cbtIds = $course->chapters->flatMap->modules->whereNotNull('cbt_id')->pluck('cbt_id')->toArray();
                    $completedCbtIds = CbtSession::where('siswa_id', $siswa->id)
                        ->where('status', 'completed')
                        ->whereIn('cbt_id', $cbtIds)
                        ->pluck('cbt_id')
                        ->toArray();

                    $completedCount = 0;
                    foreach ($course->chapters as $chapter) {
                        foreach ($chapter->modules as $module) {
                            if (in_array($module->id, $completedModuleIds)) {
                                $completedCount++;
                            } elseif ($module->cbt_id && in_array($module->cbt_id, $completedCbtIds)) {
                                $completedCount++;
                            }
                        }
                    }

                    $course->progress_percentage = round(($completedCount / $totalModules) * 100);
                    return $course;
                });
        } else if ($user->role === 'guru' || $user->role === 'admin') {
            $fungsionaris = $user->fungsionaris;
            
            if ($user->role === 'guru' && !$fungsionaris) {
                return view('elearning.index', [
                    'elearnings' => collect(),
                    'subjects' => Subject::all(),
                ])->with('error', 'Profil Guru tidak ditemukan. Silakan hubungi Admin.');
            }

            $elearnings = ELearning::with(['subject', 'chapters'])
                ->when($user->role === 'guru', function($query) use ($fungsionaris) {
                    return $query->where('teacher_id', $fungsionaris->id);
                })
                ->get();
        } else {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $subjects = Subject::all();

        return view('elearning.index', compact('elearnings', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $fungsionaris = $user->fungsionaris;

        if (!$fungsionaris && $user->role !== 'admin') {
            return back()->with('error', 'Profil fungsionaris tidak ditemukan.');
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('elearning/thumbnails', 'public');
        }

        ELearning::create([
            'subject_id' => $request->subject_id,
            'teacher_id' => $user->role === 'admin' ? Subject::find($request->subject_id)->guru_id : $fungsionaris->id,
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
        ]);

        return back()->with('success', 'E-Learning berhasil dibuat.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $elearning = ELearning::with(['subject', 'chapters.modules.cbt'])->findOrFail($id);

        if ($user->role === 'siswa') {
            $siswa = $user->siswa;
            if (!$siswa) {
                return redirect()->route('dashboard')->with('error', 'Profil Siswa tidak ditemukan.');
            }

            $hasAccess = Schedule::where('kelas_id', $siswa->kelas_id)
                ->where('subject_id', $elearning->subject_id)
                ->exists();

            if (!$hasAccess) {
                return redirect()->route('elearning.index')->with('error', 'Anda tidak memiliki akses ke e-learning ini.');
            }

            // Calculate progress per module
            $completedModuleIds = ELearningProgress::where('siswa_id', $siswa->id)
                ->whereIn('module_id', $elearning->chapters->flatMap->modules->pluck('id'))
                ->pluck('module_id')
                ->toArray();

            $cbtIds = $elearning->chapters->flatMap->modules->whereNotNull('cbt_id')->pluck('cbt_id')->toArray();
            $completedCbtIds = CbtSession::where('siswa_id', $siswa->id)
                ->where('status', 'completed')
                ->whereIn('cbt_id', $cbtIds)
                ->pluck('cbt_id')
                ->toArray();

            foreach ($elearning->chapters as $chapter) {
                foreach ($chapter->modules as $module) {
                    $module->is_completed = in_array($module->id, $completedModuleIds) || 
                                          ($module->cbt_id && in_array($module->cbt_id, $completedCbtIds));
                }
            }
        }

        $cbts = Cbt::orderBy('nama_cbt')->get();
        return view('elearning.show', compact('elearning', 'cbts'));
    }

    public function storeChapter(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $elearning = ELearning::findOrFail($id);
        $lastOrder = ELearningChapter::where('e_learning_id', $id)->max('order') ?? 0;

        ELearningChapter::create([
            'e_learning_id' => $id,
            'title' => $request->title,
            'order' => $lastOrder + 1,
        ]);

        return back()->with('success', 'BAB berhasil ditambahkan.');
    }

    public function storeModule(Request $request, $chapter_id)
    {
        $request->validate([
            'type' => 'required|in:material,assignment,exercise,exam',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'cbt_id' => 'nullable|exists:cbts,id',
            'due_date' => 'nullable|date',
        ]);

        $lastOrder = ELearningModule::where('chapter_id', $chapter_id)->max('order') ?? 0;

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('elearning/modules', 'public');
        }

        ELearningModule::create([
            'chapter_id' => $chapter_id,
            'type' => $request->type,
            'title' => $request->title,
            'content' => $request->content,
            'file_path' => $filePath,
            'cbt_id' => $request->cbt_id,
            'due_date' => $request->due_date,
            'order' => $lastOrder + 1,
        ]);

        return back()->with('success', 'Modul berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $elearning = ELearning::findOrFail($id);
        if ($elearning->thumbnail) {
            Storage::disk('public')->delete($elearning->thumbnail);
        }
        $elearning->delete();
        return back()->with('success', 'E-Learning berhasil dihapus.');
    }

    public function destroyChapter($id)
    {
        $chapter = ELearningChapter::findOrFail($id);
        $chapter->delete();
        return back()->with('success', 'BAB berhasil dihapus.');
    }

    public function destroyModule($id)
    {
        $module = ELearningModule::findOrFail($id);
        if ($module->file_path) {
            Storage::disk('public')->delete($module->file_path);
        }
        $module->delete();
        return back()->with('success', 'Modul berhasil dihapus.');
    }

    public function viewModule($id)
    {
        $user = Auth::user();
        $module = ELearningModule::with(['chapter.course.subject', 'cbt'])->findOrFail($id);
        $submission = null;

        if ($user->role === 'siswa') {
            $siswa = $user->siswa;
            if (!$siswa) {
                return redirect()->route('dashboard')->with('error', 'Profil Siswa tidak ditemukan.');
            }

            $hasAccess = Schedule::where('kelas_id', $siswa->kelas_id)
                ->where('subject_id', $module->chapter->course->subject_id)
                ->exists();

            if (!$hasAccess) {
                return redirect()->route('elearning.index')->with('error', 'Anda tidak memiliki akses ke modul ini.');
            }

            $isCompleted = ELearningProgress::where('siswa_id', $siswa->id)
                ->where('module_id', $id)
                ->exists();
            
            if (!$isCompleted && $module->cbt_id) {
                $isCompleted = CbtSession::where('siswa_id', $siswa->id)
                    ->where('cbt_id', $module->cbt_id)
                    ->where('status', 'completed')
                    ->exists();
            }

            $module->is_completed = $isCompleted;

            if ($module->type === 'assignment') {
                $submission = \App\Models\ELearningSubmission::where('module_id', $id)
                    ->where('siswa_id', $siswa->id)
                    ->first();
            }
        } else {
             if ($module->type === 'assignment') {
                $module->load('submissions.siswa.kelas');
             } elseif (($module->type === 'exam' || $module->type === 'exercise') && $module->cbt_id) {
                $module->cbt->load('sessions.siswa.kelas');
             }
        }

        return view('elearning.module', compact('module', 'submission'));
    }

    public function completeModule($id)
    {
        $user = Auth::user();
        if ($user->role !== 'siswa') {
            return back()->with('error', 'Hanya siswa yang dapat menandai modul selesai.');
        }

        $siswa = $user->siswa;
        $module = ELearningModule::findOrFail($id);

        ELearningProgress::updateOrCreate([
            'siswa_id' => $siswa->id,
            'module_id' => $id,
        ], [
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Modul berhasil ditandai selesai.');
    }

    public function submitAssignment(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'siswa') {
            return back()->with('error', 'Hanya siswa yang dapat mengirim tugas.');
        }

        $siswa = $user->siswa;
        $module = ELearningModule::findOrFail($id);

        if ($module->type !== 'assignment') {
            return back()->with('error', 'Modul ini bukan penugasan.');
        }

        $request->validate([
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('elearning/submissions', 'public');
        }

        $submission = \App\Models\ELearningSubmission::updateOrCreate([
            'module_id' => $id,
            'siswa_id' => $siswa->id,
        ], [
            'content' => $request->content,
            'file_path' => $filePath ?? \App\Models\ELearningSubmission::where('module_id', $id)->where('siswa_id', $siswa->id)->value('file_path'),
        ]);

        // Also mark as completed
        ELearningProgress::updateOrCreate([
            'siswa_id' => $siswa->id,
            'module_id' => $id,
        ], [
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Tugas berhasil dikirim.');
    }

    public function gradeSubmission(Request $request, $submission_id)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $submission = \App\Models\ELearningSubmission::findOrFail($submission_id);
        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
        ]);

        // Notify student
        if ($submission->siswa && $submission->siswa->user) {
            $submission->siswa->user->notify(new \App\Notifications\GeneralNotification([
                'type' => 'elearning_graded',
                'icon' => 'grade',
                'color' => 'yellow',
                'title' => 'Tugas Dinilai',
                'message' => 'Tugas Anda telah dinilai: ' . ($submission->eLearning->judul ?? 'Tugas E-Learning'),
                'url' => route('elearning.show', $submission->e_learning_id),
                'action_type' => 'elearning_detail',
                'action_id' => $submission->e_learning_id
            ]));
        }

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}
