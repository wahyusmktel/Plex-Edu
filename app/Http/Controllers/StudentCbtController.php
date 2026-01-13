<?php

namespace App\Http\Controllers;

use App\Models\Cbt;
use App\Models\CbtSession;
use App\Models\CbtAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentCbtController extends Controller
{
    public function index()
    {
        return view('student.cbt.enter-token');
    }

    public function join(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:5',
        ]);

        $cbt = Cbt::where('token', strtoupper($request->token))->first();

        if (!$cbt) {
            return back()->withErrors(['token' => 'Token tidak valid.']);
        }

        $siswa = Auth::user()->siswa;
        if (!$siswa) {
            return back()->withErrors(['token' => 'Hanya siswa yang dapat mengikuti ujian.']);
        }

        // Check for existing completed session
        $completedSession = CbtSession::where('cbt_id', $cbt->id)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'completed')
            ->first();

        if ($completedSession) {
            return redirect()->route('test.result', $completedSession->id);
        }

        // Check date and time
        $now = now();
        $startTime = \Carbon\Carbon::parse($cbt->tanggal->format('Y-m-d') . ' ' . $cbt->jam_mulai);
        $endTime = \Carbon\Carbon::parse($cbt->tanggal->format('Y-m-d') . ' ' . $cbt->jam_selesai);

        if ($now->lt($startTime)) {
            return back()->withErrors(['token' => 'Ujian belum dimulai. Dimulai pada ' . $startTime->format('H:i')]);
        }

        if ($now->gt($endTime)) {
            return back()->withErrors(['token' => 'Ujian telah berakhir.']);
        }

        // Check for existing ongoing session or create new
        $session = CbtSession::firstOrCreate(
            [
                'cbt_id' => $cbt->id,
                'siswa_id' => $siswa->id,
                'status' => 'ongoing'
            ],
            [
                'start_time' => now()
            ]
        );

        return redirect()->route('test.exam', $session->id);
    }

    public function exam($session_id)
    {
        $session = CbtSession::with(['cbt.questions.options', 'cbt.subject', 'answers'])->findOrFail($session_id);
        
        if ($session->status == 'completed') {
            return redirect()->route('test.result', $session->id);
        }

        // Get already answered question IDs
        $answeredQuestionIds = $session->answers->pluck('question_id')->toArray();

        return view('student.cbt.exam', compact('session', 'answeredQuestionIds'));
    }

    public function saveAnswer(Request $request, $session_id)
    {
        $session = CbtSession::with('cbt.questions.options')->findOrFail($session_id);
        
        if ($session->status == 'completed') {
            return response()->json(['message' => 'Ujian sudah selesai.'], 422);
        }

        $questionId = $request->input('question_id');
        $answer = $request->input('answer');
        
        $question = $session->cbt->questions->where('id', $questionId)->first();
        if (!$question) {
            return response()->json(['message' => 'Soal tidak ditemukan.'], 404);
        }

        $poin = 0;
        $optionId = null;
        $essayAnswer = null;

        if ($question->jenis_soal == 'pilihan_ganda') {
            $optionId = $answer;
            $correctOption = $question->options->where('is_correct', true)->first();
            if ($correctOption && $correctOption->id == $optionId) {
                $poin = $question->poin;
            }
        } else {
            $essayAnswer = $answer;
        }

        CbtAnswer::updateOrCreate(
            [
                'session_id' => $session->id,
                'question_id' => $questionId
            ],
            [
                'option_id' => $optionId,
                'essay_answer' => $essayAnswer,
                'poin_didapat' => $poin,
                'is_graded' => $question->jenis_soal == 'pilihan_ganda'
            ]
        );

        return response()->json(['success' => true]);
    }

    public function submit(Request $request, $session_id)
    {
        $session = CbtSession::with('cbt.questions.options')->findOrFail($session_id);
        
        if ($session->status == 'completed') {
            return response()->json(['message' => 'Ujian sudah dikirim sebelumnya.'], 422);
        }

        // Check if all questions answered
        $answeredCount = CbtAnswer::where('session_id', $session->id)->count();
        $questionCount = $session->cbt->questions->count();

        if ($answeredCount < $questionCount) {
            return response()->json([
                'message' => 'Masih ada ' . ($questionCount - $answeredCount) . ' soal yang belum dijawab. Harap selesaikan sebelum mengirim.'
            ], 422);
        }

        // Calculate total score from saved answers
        $totalSkor = CbtAnswer::where('session_id', $session->id)->sum('poin_didapat');

        $session->update([
            'end_time' => now(),
            'skor' => $totalSkor,
            'status' => 'completed'
        ]);

        $showResult = $session->cbt->show_result;

        return response()->json([
            'success' => 'Ujian berhasil dikirim. Terima kasih!',
            'show_result' => $showResult,
            'result_url' => $showResult ? route('test.result', $session->id) : null
        ]);
    }

    public function result($session_id)
    {
        $session = CbtSession::with(['cbt.questions.options', 'cbt.subject', 'answers', 'siswa'])->findOrFail($session_id);
        
        if ($session->status != 'completed') {
            return redirect()->route('test.exam', $session->id);
        }

        // Check if result should be shown
        if (!$session->cbt->show_result) {
            return view('student.cbt.result-hidden', compact('session'));
        }

        return view('student.cbt.result', compact('session'));
    }
}

