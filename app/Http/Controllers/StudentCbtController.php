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

        // Check date and time
        $now = now();
        $startTime = \Carbon\Carbon::parse($cbt->tanggal . ' ' . $cbt->jam_mulai);
        $endTime = \Carbon\Carbon::parse($cbt->tanggal . ' ' . $cbt->jam_selesai);

        if ($now->lt($startTime)) {
            return back()->withErrors(['token' => 'Ujian belum dimulai. Dimulai pada ' . $startTime->format('H:i')]);
        }

        if ($now->gt($endTime)) {
            return back()->withErrors(['token' => 'Ujian telah berakhir.']);
        }

        $siswa = Auth::user()->siswa;
        if (!$siswa) {
            return back()->withErrors(['token' => 'Hanya siswa yang dapat mengikuti ujian.']);
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
        $session = CbtSession::with(['cbt.questions.options', 'cbt.subject'])->findOrFail($session_id);
        
        if ($session->status == 'completed') {
            return redirect()->route('test.index')->with('info', 'Anda telah menyelesaikan ujian ini.');
        }

        return view('student.cbt.exam', compact('session'));
    }

    public function submit(Request $request, $session_id)
    {
        $session = CbtSession::with('cbt.questions.options')->findOrFail($session_id);
        
        if ($session->status == 'completed') {
            return response()->json(['message' => 'Ujian sudah dikirim sebelumnya.'], 422);
        }

        $answers = $request->input('answers', []);
        $totalSkor = 0;

        foreach ($session->cbt->questions as $question) {
            $studentAnswer = $answers[$question->id] ?? null;
            $poin = 0;
            $optionId = null;
            $essayAnswer = null;

            if ($question->jenis_soal == 'pilihan_ganda') {
                $optionId = $studentAnswer;
                $correctOption = $question->options->where('is_correct', true)->first();
                if ($correctOption && $correctOption->id == $optionId) {
                    $poin = $question->poin;
                }
            } else {
                $essayAnswer = $studentAnswer;
                // Essay needs manual grading, so poin 0 for now
            }

            CbtAnswer::updateOrCreate(
                [
                    'session_id' => $session->id,
                    'question_id' => $question->id
                ],
                [
                    'option_id' => $optionId,
                    'essay_answer' => $essayAnswer,
                    'poin_didapat' => $poin,
                    'is_graded' => $question->jenis_soal == 'pilihan_ganda'
                ]
            );

            $totalSkor += $poin;
        }

        $session->update([
            'end_time' => now(),
            'skor' => $totalSkor,
            'status' => 'completed'
        ]);

        return response()->json(['success' => 'Ujian berhasil dikirim. Terima kasih!']);
    }
}
