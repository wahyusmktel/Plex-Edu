<?php

namespace App\Http\Controllers;

use App\Models\LibraryItem;
use App\Models\LibraryLoan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LibraryController extends Controller
{
    public function index()
    {
        $books = LibraryItem::where('category', 'book')->latest()->get();
        $audios = LibraryItem::where('category', 'audio')->latest()->get();
        $videos = LibraryItem::where('category', 'video')->latest()->get();
        
        return view('pages.library.index', compact('books', 'audios', 'videos'));
    }

    public function create()
    {
        return view('pages.library.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|in:book,audio,video',
            'description' => 'nullable|string',
            'file' => 'required|file',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $file_path = $request->file('file')->store('library/' . $request->category, 'public');
        $cover_image = null;
        if ($request->hasFile('cover_image')) {
            $cover_image = $request->file('cover_image')->store('library/covers', 'public');
        }

        LibraryItem::create([
            'title' => $request->title,
            'author' => $request->author,
            'category' => $request->category,
            'description' => $request->description,
            'file_path' => $file_path,
            'cover_image' => $cover_image,
        ]);

        return redirect()->route('library.index')->with('success', 'Item perpustakaan berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $item = LibraryItem::findOrFail($id);
        Storage::disk('public')->delete($item->file_path);
        if ($item->cover_image) {
            Storage::disk('public')->delete($item->cover_image);
        }
        $item->delete();

        return redirect()->route('library.index')->with('success', 'Item perpustakaan berhasil dihapus.');
    }

    public function loans()
    {
        $loans = LibraryLoan::with(['student.kelas', 'item'])->latest()->get();
        $students = Siswa::latest()->get();
        $items = LibraryItem::latest()->get();
        
        return view('pages.library.loans', compact('loans', 'students', 'items'));
    }

    public function storeLoan(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:siswas,id',
            'library_item_id' => 'required|exists:library_items,id',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:loan_date',
        ]);

        LibraryLoan::create([
            'student_id' => $request->student_id,
            'library_item_id' => $request->library_item_id,
            'loan_date' => $request->loan_date,
            'due_date' => $request->due_date,
            'status' => 'borrowed',
        ]);

        return redirect()->route('library.loans')->with('success', 'Transaksi peminjaman berhasil dicatat.');
    }

    public function returnLoan($id)
    {
        $loan = LibraryLoan::findOrFail($id);
        $loan->update([
            'return_date' => now(),
            'status' => 'returned',
        ]);

        return redirect()->route('library.loans')->with('success', 'Buku telah dikembalikan.');
    }
}
