<?php

namespace App\Http\Controllers;

use App\Models\Cbt;
use App\Models\Subject;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\CbtQuestion;
use App\Models\CbtOption;
use App\Models\CbtAnswer;
use App\Models\BankSoal;
use App\Models\BankSoalQuestion;
use App\Models\BankSoalOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CbtController extends Controller
{
    public function index(Request $request)
    {
        $query = Cbt::with(['subject', 'creator']);
        
        if ($request->has('search')) {
            $query->where('nama_cbt', 'like', '%' . $request->search . '%');
        }

        $cbts = $query->paginate(10)->withQueryString();
        $subjects = Subject::where('is_active', true)->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $siswaList = Siswa::with('kelas')->orderBy('nama_lengkap')->get();

        return view('admin.cbt.index', compact('cbts', 'subjects', 'kelasList', 'siswaList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_cbt' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'subject_id' => 'nullable|exists:subjects,id',
            'skor_maksimal' => 'required|integer|min:1',
            'participant_type' => 'required|in:all,kelas,siswa',
            'selected_kelas' => 'nullable|array|required_if:participant_type,kelas',
            'selected_siswa' => 'nullable|array|required_if:participant_type,siswa',
        ]);

        $data = $request->all();
        $data['show_result'] = $request->boolean('show_result');
        $data['created_by'] = Auth::id();

        $cbt = Cbt::create($data);

        if ($request->participant_type === 'kelas' && $request->has('selected_kelas')) {
            $cbt->allowedKelas()->attach($request->selected_kelas);
        }

        if ($request->participant_type === 'siswa' && $request->has('selected_siswa')) {
            $cbt->allowedSiswas()->attach($request->selected_siswa);
        }

        return response()->json(['success' => 'CBT berhasil ditambahkan']);
    }

    public function show($id)
    {
        $cbt = Cbt::with(['subject', 'allowedKelas', 'allowedSiswas'])->findOrFail($id);
        $data = $cbt->toArray();
        $data['tanggal'] = $cbt->tanggal->format('Y-m-d');
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_cbt' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'subject_id' => 'nullable|exists:subjects,id',
            'skor_maksimal' => 'required|integer|min:1',
            'participant_type' => 'required|in:all,kelas,siswa',
            'selected_kelas' => 'nullable|array|required_if:participant_type,kelas',
            'selected_siswa' => 'nullable|array|required_if:participant_type,siswa',
        ]);

        $cbt = Cbt::findOrFail($id);
        $data = $request->all();
        $data['show_result'] = $request->boolean('show_result');
        $cbt->update($data);

        if ($request->participant_type === 'kelas') {
            $cbt->allowedKelas()->sync($request->selected_kelas ?? []);
            $cbt->allowedSiswas()->detach(); // Clear siswas if switching to kelas
        } elseif ($request->participant_type === 'siswa') {
            $cbt->allowedSiswas()->sync($request->selected_siswa ?? []);
            $cbt->allowedKelas()->detach(); // Clear kelas if switching to siswa
        } else {
            // If type is 'all', clear specified restrictions
            $cbt->allowedKelas()->detach();
            $cbt->allowedSiswas()->detach();
        }

        return response()->json(['success' => 'CBT berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Cbt::destroy($id);
        return response()->json(['success' => 'CBT berhasil dihapus']);
    }

    public function questions($id)
    {
        $cbt = Cbt::with(['subject', 'questions.options'])->findOrFail($id);
        $bankSoals = BankSoal::where('subject_id', $cbt->subject_id)
            ->where(function($q) {
                $q->where('teacher_id', Auth::user()->fungsionaris->id ?? Auth::id())
                  ->orWhere('status', 'public');
            })
            ->latest()
            ->get();

        return view('admin.cbt.questions', compact('cbt', 'bankSoals'));
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'cbt_id' => 'required|exists:cbts,id',
            'jenis_soal' => 'required|in:pilihan_ganda,essay',
            'pertanyaan' => 'required|string',
            'poin' => 'required|integer|min:0',
            'gambar' => 'nullable|image|max:2048',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'correct_option' => 'nullable|integer'
        ]);

        // Max score validation
        $cbt = Cbt::findOrFail($request->cbt_id);
        $currentTotalPoin = $cbt->questions()->sum('poin');
        if ($currentTotalPoin + $request->poin > $cbt->skor_maksimal) {
            return response()->json(['message' => 'Total poin melebihi skor maksimal CBT ' . $cbt->skor_maksimal], 422);
        }

        $data = $request->only(['cbt_id', 'jenis_soal', 'pertanyaan', 'poin']);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('cbt/questions', 'public');
        }

        $question = $cbt->questions()->create($data);

        if ($request->jenis_soal == 'pilihan_ganda' && $request->has('options')) {
            foreach ($request->options as $index => $optText) {
                if ($optText) {
                    $question->options()->create([
                        'opsi' => $optText,
                        'is_correct' => $request->correct_option == $index
                    ]);
                }
            }
        }

        return response()->json(['success' => 'Soal berhasil ditambahkan']);
    }

    public function showQuestion($question_id)
    {
        return response()->json(CbtQuestion::with('options')->findOrFail($question_id));
    }

    public function updateQuestion(Request $request, $question_id)
    {
        $question = CbtQuestion::findOrFail($question_id);
        $cbt = $question->cbt;

        $request->validate([
            'pertanyaan' => 'required|string',
            'poin' => 'required|integer|min:0',
            'gambar' => 'nullable|image|max:2048',
        ]);

        // Max score validation for update
        $currentTotalPoin = $cbt->questions()->where('id', '!=', $question_id)->sum('poin');
        if ($currentTotalPoin + $request->poin > $cbt->skor_maksimal) {
            return response()->json(['message' => 'Total poin melebihi skor maksimal CBT ' . $cbt->skor_maksimal], 422);
        }

        $data = $request->only(['pertanyaan', 'poin']);
        if ($request->hasFile('gambar')) {
            if ($question->gambar) Storage::disk('public')->delete($question->gambar);
            $data['gambar'] = $request->file('gambar')->store('cbt/questions', 'public');
        }

        $question->update($data);

        if ($question->jenis_soal == 'pilihan_ganda' && $request->has('options')) {
            $question->options()->delete();
            foreach ($request->options as $index => $optText) {
                if ($optText) {
                    $question->options()->create([
                        'opsi' => $optText,
                        'is_correct' => $request->correct_option == $index
                    ]);
                }
            }
        }

        return response()->json(['success' => 'Soal berhasil diperbarui']);
    }

    public function destroyQuestion($question_id)
    {
        $question = CbtQuestion::findOrFail($question_id);
        if ($question->gambar) Storage::disk('public')->delete($question->gambar);
        $question->delete();
        return response()->json(['success' => 'Soal berhasil dihapus']);
    }

    public function results($id)
    {
        $cbt = Cbt::with(['subject', 'sessions.siswa', 'sessions.answers', 'questions'])->findOrFail($id);
        return view('admin.cbt.results', compact('cbt'));
    }

    public function exportExcel($id)
    {
        $cbt = Cbt::with(['subject', 'sessions.siswa', 'sessions.answers', 'questions'])->findOrFail($id);
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CbtResultsExport($cbt), 'hasil_cbt_' . str_replace(' ', '_', $cbt->nama_cbt) . '.xlsx');
    }

    public function exportPdf($id)
    {
        $cbt = Cbt::with(['subject', 'sessions.siswa', 'sessions.answers', 'questions'])->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cbt-results', compact('cbt'));
        return $pdf->download('hasil_cbt_' . str_replace(' ', '_', $cbt->nama_cbt) . '.pdf');
    }

    public function analysis($id)
    {
        $cbt = Cbt::with(['subject', 'questions.answers', 'questions.options'])->findOrFail($id);
        return view('admin.cbt.analysis', compact('cbt'));
    }

    public function gradeEssay($question_id)
    {
        $question = CbtQuestion::with(['cbt.subject', 'answers.session.siswa', 'answers.session.cbt'])->findOrFail($question_id);
        
        // Ensure it's an essay question
        if ($question->jenis_soal != 'essay') {
            return redirect()->back()->with('error', 'Bukan soal esai.');
        }

        return view('admin.cbt.grade-essay', compact('question'));
    }

    public function storeGrade(Request $request)
    {
        $request->validate([
            'answer_id' => 'required|exists:cbt_answers,id',
            'poin' => 'required|integer|min:0',
        ]);

        $answer = CbtAnswer::with('question', 'session')->findOrFail($request->answer_id);

        if ($request->poin > $answer->question->poin) {
            return response()->json(['message' => 'Poin tidak boleh melebihi poin maksimal soal (' . $answer->question->poin . ')'], 422);
        }

        $answer->update([
            'poin_didapat' => $request->poin,
            'is_graded' => true
        ]);

        // Recalculate total score for the session
        $totalScore = $answer->session->answers->sum('poin_didapat');
        $answer->session->update(['skor' => $totalScore]);

        return response()->json(['success' => 'Nilai berhasil disimpan']);
    }

    public function importFromBankSoal(Request $request, $cbt_id)
    {
        $request->validate([
            'bank_soal_id' => 'required|exists:bank_soals,id'
        ]);

        $cbt = Cbt::findOrFail($cbt_id);
        $bankSoal = BankSoal::with('questions.options')->findOrFail($request->bank_soal_id);

        $currentTotalPoin = $cbt->questions()->sum('poin');
        $importedCount = 0;

        foreach ($bankSoal->questions as $question) {
            // Check if adding this question exceeds max score
            if ($currentTotalPoin + $question->poin > $cbt->skor_maksimal) {
                continue; // Skip or handle error
            }

            $newQuestion = $cbt->questions()->create([
                'jenis_soal' => $question->jenis_soal,
                'pertanyaan' => $question->pertanyaan,
                'gambar' => $question->gambar, // Note: This might need file copying if storage paths differ significantly
                'poin' => $question->poin,
            ]);

            if ($question->jenis_soal === 'pilihan_ganda') {
                foreach ($question->options as $option) {
                    $newQuestion->options()->create([
                        'opsi' => $option->opsi,
                        'gambar' => $option->gambar,
                        'is_correct' => $option->is_correct,
                    ]);
                }
            }

            $currentTotalPoin += $question->poin;
            $importedCount++;
        }

        return back()->with('success', $importedCount . ' soal berhasil diimpor dari ' . $bankSoal->title);
    }
}

