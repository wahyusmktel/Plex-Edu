<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use App\Models\BankSoalQuestion;
use App\Models\BankSoalOption;
use App\Models\Subject;
use App\Imports\BankSoalQuestionImport;
use App\Exports\BankSoalTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class BankSoalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $fungsionaris = $user->fungsionaris;

        if (!$fungsionaris && $user->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Profil fungsionaris tidak ditemukan.');
        }

        $query = BankSoal::with(['subject', 'questions'])
            ->when($user->role === 'guru', function($q) use ($fungsionaris) {
                return $q->where('teacher_id', $fungsionaris->id);
            });

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $bankSoals = $query->latest()->paginate(10)->withQueryString();
        $subjects = Subject::where('is_active', true)->get();
        $levels = Kelas::distinct()->pluck('tingkat')->sort()->values();

        return view('bank-soal.index', compact('bankSoals', 'subjects', 'levels'));
    }

    public function archive(Request $request)
    {
        $user = Auth::user();
        $fungsionaris = $user->fungsionaris;

        $query = BankSoal::withoutGlobalScope('school')
            ->with([
                'subject' => function($q) {
                    $q->withoutGlobalScope('school');
                }, 
                'teacher' => function($q) {
                    $q->withoutGlobalScope('school');
                }, 
                'school',
                'questions'
            ])
            ->where('status', 'public');

        if ($user->role === 'guru' && $fungsionaris) {
            $query->where('teacher_id', '!=', $fungsionaris->id);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $bankSoals = $query->latest()->paginate(10)->withQueryString();
        $subjects = Subject::where('is_active', true)->get();
        $levels = Kelas::distinct()->pluck('tingkat')->sort()->values();

        return view('bank-soal.archive', compact('bankSoals', 'subjects', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'level' => 'required|string',
            'status' => 'required|in:private,public',
        ]);

        $user = Auth::user();
        $fungsionaris = $user->fungsionaris;

        if (!$fungsionaris && $user->role !== 'admin') {
            return back()->with('error', 'Profil fungsionaris tidak ditemukan.');
        }

        BankSoal::create([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'teacher_id' => $user->role === 'admin' ? Subject::find($request->subject_id)->teacher_id : $fungsionaris->id,
            'level' => $request->level,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Bank Soal berhasil dibuat.');
    }

    public function show($id)
    {
        $bankSoal = BankSoal::withoutGlobalScope('school')
            ->with([
                'subject' => function($q) {
                    $q->withoutGlobalScope('school');
                }, 
                'teacher' => function($q) {
                    $q->withoutGlobalScope('school');
                },
                'school',
                'questions.options'
            ])
            ->findOrFail($id);

        // Check if the bank soal belongs to the user's school OR is public
        if ($bankSoal->school_id !== Auth::user()->school_id && $bankSoal->status !== 'public') {
            abort(403, 'Anda tidak memiliki akses ke Bank Soal ini.');
        }

        return view('bank-soal.show', compact('bankSoal'));
    }

    public function update(Request $request, $id)
    {
        $bankSoal = BankSoal::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|string',
            'status' => 'required|in:private,public',
        ]);

        $bankSoal->update($request->only(['title', 'level', 'status']));

        return back()->with('success', 'Bank Soal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bankSoal = BankSoal::findOrFail($id);
        $bankSoal->delete();
        return redirect()->route('bank-soal.index')->with('success', 'Bank Soal berhasil dihapus.');
    }

    public function storeQuestion(Request $request, $id)
    {
        $request->validate([
            'jenis_soal' => 'required|in:pilihan_ganda,essay',
            'pertanyaan' => 'required|string',
            'poin' => 'required|integer|min:0',
            'gambar' => 'nullable|image|max:2048',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'correct_option' => 'nullable|integer'
        ]);

        $bankSoal = BankSoal::findOrFail($id);
        $data = $request->only(['jenis_soal', 'pertanyaan', 'poin']);
        
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('bank-soal/questions', 'public');
        }

        $question = $bankSoal->questions()->create($data);

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

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function updateQuestion(Request $request, $question_id)
    {
        $question = BankSoalQuestion::findOrFail($question_id);
        $request->validate([
            'pertanyaan' => 'required|string',
            'poin' => 'required|integer|min:0',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['pertanyaan', 'poin']);
        if ($request->hasFile('gambar')) {
            if ($question->gambar) Storage::disk('public')->delete($question->gambar);
            $data['gambar'] = $request->file('gambar')->store('bank-soal/questions', 'public');
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

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroyQuestion($question_id)
    {
        $question = BankSoalQuestion::findOrFail($question_id);
        if ($question->gambar) Storage::disk('public')->delete($question->gambar);
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    public function importQuestions(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $bankSoal = BankSoal::withoutGlobalScope('school')->findOrFail($id);
        if ($bankSoal->school_id !== Auth::user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke Bank Soal ini.');
        }

        try {
            Excel::import(new BankSoalQuestionImport($bankSoal->id), $request->file('file'));
            return back()->with('success', 'Soal berhasil diimport ke bank soal.');
        } catch (ValidationException $e) {
            $errors = collect($e->failures())->map(function ($failure) {
                return 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            })->implode(' | ');

            return back()->with('error', $errors ?: 'Gagal import soal.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import soal.');
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new BankSoalTemplateExport, 'template_import_bank_soal.xlsx');
    }
}
