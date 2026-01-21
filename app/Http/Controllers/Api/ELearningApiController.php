<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ELearning;
use App\Models\ELearningChapter;
use App\Models\ELearningModule;
use App\Models\ELearningProgress;
use App\Models\ELearningSubmission;
use App\Models\Cbt;
use App\Models\CbtQuestion;
use App\Models\CbtOption;
use App\Models\CbtAnswer;
use App\Models\CbtSession;
use App\Models\Schedule;
use App\Models\Siswa;
use Illuminate\Http\Request;

class ELearningApiController extends Controller
{
    public function getCourses(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'siswa') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa record not found'], 404);
        }

        $subjectIds = Schedule::where('kelas_id', $siswa->kelas_id)
            ->pluck('subject_id')
            ->unique();

        $elearnings = ELearning::with(['subject', 'teacher', 'chapters.modules'])
            ->whereIn('subject_id', $subjectIds)
            ->get()
            ->map(function ($course) use ($siswa) {
                $totalModules = $course->chapters->flatMap->modules->count();
                
                if ($totalModules === 0) {
                    $course->progress_percentage = 0;
                    $course->completed_count = 0;
                    $course->total_modules = 0;
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
                $course->completed_count = $completedCount;
                $course->total_modules = $totalModules;
                
                // Hide chapters and modules info for list view to save bandwidth
                unset($course->chapters);
                
                return $course;
            });

        return response()->json([
            'status' => 'success',
            'data' => $elearnings
        ]);
    }

    public function getCourseDetail(Request $request, $id)
    {
        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        $elearning = ELearning::with(['subject', 'teacher', 'chapters.modules.cbt'])->findOrFail($id);

        if ($user->role === 'siswa') {
            $hasAccess = Schedule::where('kelas_id', $siswa->kelas_id)
                ->where('subject_id', $elearning->subject_id)
                ->exists();

            if (!$hasAccess) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized access to this course'], 403);
            }

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

        return response()->json([
            'status' => 'success',
            'data' => $elearning
        ]);
    }

    public function viewModule(Request $request, $id)
    {
        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        $module = ELearningModule::with(['chapter.course.subject', 'cbt'])->findOrFail($id);

        if ($user->role === 'siswa') {
            $hasAccess = Schedule::where('kelas_id', $siswa->kelas_id)
                ->where('subject_id', $module->chapter->course->subject_id)
                ->exists();

            if (!$hasAccess) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized access to this module'], 403);
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
        }

        return response()->json([
            'status' => 'success',
            'data' => $module
        ]);
    }

    public function completeModule(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'siswa') {
            return response()->json(['status' => 'error', 'message' => 'Only students can mark modules as completed'], 403);
        }

        $siswa = Siswa::where('user_id', $user->id)->first();
        $module = ELearningModule::findOrFail($id);

        ELearningProgress::updateOrCreate([
            'siswa_id' => $siswa->id,
            'module_id' => $id,
        ], [
            'completed_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Module marked as completed'
        ]);
    }

    public function submitAssignment(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'siswa') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $siswa = Siswa::where('user_id', $user->id)->first();
        $module = ELearningModule::findOrFail($id);

        if ($module->type !== 'assignment') {
            return response()->json(['status' => 'error', 'message' => 'This module is not an assignment'], 400);
        }

        $request->validate([
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('elearning/submissions', 'public');
        }

        $submission = ELearningSubmission::updateOrCreate([
            'module_id' => $id,
            'siswa_id' => $siswa->id,
        ], [
            'content' => $request->content,
            'file_path' => $filePath ?? ELearningSubmission::where('module_id', $id)->where('siswa_id', $siswa->id)->value('file_path'),
        ]);

        // Also mark as completed in progress table
        ELearningProgress::updateOrCreate([
            'siswa_id' => $siswa->id,
            'module_id' => $id,
        ], [
            'completed_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Assignment submitted successfully',
            'data' => $submission
        ]);
    }

    public function getSubmission(Request $request, $id)
    {
        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        $submission = ELearningSubmission::where('module_id', $id)
            ->where('siswa_id', $siswa->id)
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => $submission
        ]);
    }

    public function getCbtList(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'siswa') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $cbts = Cbt::withoutGlobalScope('school')
            ->with(['subject' => function($q) {
                $q->withoutGlobalScope('school');
            }])
            ->where(function($q) use ($user) {
                $q->where('school_id', $user->school_id)
                  ->orWhereNull('school_id');
            })
            ->orderBy('tanggal', 'desc')
            ->get()
            ->filter(function ($cbt) use ($siswa) {
                return $cbt->canParticipate($siswa);
            })
            ->map(function ($cbt) use ($siswa) {
                // Check if student already has a session
                $session = CbtSession::where('cbt_id', $cbt->id)
                    ->where('siswa_id', $siswa->id)
                    ->first();

                return [
                    'id' => $cbt->id,
                    'nama_cbt' => $cbt->nama_cbt,
                    'subject' => $cbt->subject?->nama_pelajaran ?? 'N/A',
                    'tanggal' => $cbt->tanggal->format('d M Y'),
                    'jam_mulai' => $cbt->jam_mulai,
                    'jam_selesai' => $cbt->jam_selesai,
                    'status' => $cbt->status,
                    'show_result' => $cbt->show_result,
                    'questions_count' => $cbt->questions()->count(),
                    'session_status' => $session?->status,
                    'session_score' => $session?->score,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $cbts
        ]);
    }

    public function startCbtSession(Request $request, $cbt_id)
    {
        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        $cbt = Cbt::withoutGlobalScope('school')->findOrFail($cbt_id);

        if (!$cbt->canParticipate($siswa)) {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak terdaftar untuk CBT ini'], 403);
        }

        // Validate token
        if ($request->token !== $cbt->token) {
            return response()->json(['status' => 'error', 'message' => 'Token yang Anda masukkan salah'], 422);
        }

        // Check if session already exists and is completed
        $existingSession = CbtSession::where('cbt_id', $cbt_id)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'completed')
            ->first();

        if ($existingSession) {
            return response()->json(['status' => 'error', 'message' => 'You have already completed this CBT'], 400);
        }

        $session = CbtSession::firstOrCreate([
            'cbt_id' => $cbt_id,
            'siswa_id' => $siswa->id,
            'status' => 'ongoing',
        ], [
            'start_time' => now(),
        ]);

        $session->load('cbt');

        return response()->json([
            'status' => 'success',
            'data' => $session
        ]);
    }

    public function getCbtQuestions(Request $request, $session_id)
    {
        $session = CbtSession::findOrFail($session_id);
        $questions = CbtQuestion::with('options')
            ->where('cbt_id', $session->cbt_id)
            ->get()
            ->map(function($q) {
                // Hide is_correct from being sent to client
                $q->options->makeHidden('is_correct');
                return $q;
            });

        return response()->json([
            'status' => 'success',
            'data' => $questions
        ]);
    }

    public function submitCbtAnswer(Request $request, $session_id)
    {
        $request->validate([
            'question_id' => 'required|exists:cbt_questions,id',
            'option_id' => 'nullable|exists:cbt_options,id',
            'essay_answer' => 'nullable|string',
        ]);

        $answer = CbtAnswer::updateOrCreate([
            'session_id' => $session_id,
            'question_id' => $request->question_id,
        ], [
            'option_id' => $request->option_id,
            'essay_answer' => $request->essay_answer,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $answer
        ]);
    }

    public function finishCbtSession(Request $request, $session_id)
    {
        $session = CbtSession::with(['cbt.questions.options', 'answers'])->findOrFail($session_id);
        
        if ($session->status === 'completed') {
            return response()->json(['status' => 'error', 'message' => 'Session already completed'], 400);
        }

        $totalScore = 0;
        foreach ($session->cbt->questions as $question) {
            $userAnswer = $session->answers->where('question_id', $question->id)->first();
            if ($userAnswer) {
                if ($question->jenis_soal === 'pilihan_ganda') {
                    $correctOption = $question->options->where('is_correct', true)->first();
                    if ($correctOption && $userAnswer->option_id === $correctOption->id) {
                        $userAnswer->poin_didapat = $question->poin;
                        $totalScore += $question->poin;
                    } else {
                        $userAnswer->poin_didapat = 0;
                    }
                    $userAnswer->is_graded = true;
                } else {
                    // Essay or other types might need manual grading
                    $userAnswer->is_graded = false;
                }
                $userAnswer->save();
            }
        }

        $session->skor = $totalScore;
        $session->status = 'completed';
        $session->end_time = now();
        $session->save();

        return response()->json([
            'status' => 'success',
            'message' => 'CBT session completed',
            'data' => [
                'score' => $totalScore,
                'max_score' => $session->cbt->skor_maksimal
            ]
        ]);
    }
}
