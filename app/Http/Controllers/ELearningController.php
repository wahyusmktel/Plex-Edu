<?php

namespace App\Http\Controllers;

use App\Models\ELearning;
use App\Models\ELearningChapter;
use App\Models\ELearningModule;
use App\Models\Subject;
use App\Models\Cbt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ELearningController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'guru' && $user->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

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
        $elearning = ELearning::with(['subject', 'chapters.modules.cbt'])->findOrFail($id);
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
}
