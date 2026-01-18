<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LibraryItem;
use App\Models\LibraryLoan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ELibraryApiController extends Controller
{
    public function catalog(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type', 'ebook');
        $search = $request->query('search');
        $kategori = $request->query('kategori');

        // Map Flutter types to database category values
        $categoryMap = [
            'ebook' => 'book',
            'audiobook' => 'audio',
            'videobook' => 'video',
        ];
        $category = $categoryMap[$type] ?? 'book';

        $siswa = Siswa::withoutGlobalScope('school')
            ->where('user_id', $user->id)
            ->first();

        $items = LibraryItem::withoutGlobalScope('school')
            ->where(function ($q) use ($user) {
                $q->where('school_id', $user->school_id)
                  ->orWhereNull('school_id');
            })
            ->where('category', $category)
            ->when($search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                          ->orWhere('author', 'like', "%{$search}%");
                });
            })
            ->when($kategori, function ($q, $kategori) {
                $q->where('kategori', $kategori);
            })
            ->orderBy('title', 'asc')
            ->get()
            ->map(function ($item) use ($siswa) {
                $activeLoan = $siswa ? LibraryLoan::withoutGlobalScope('school')
                    ->where('library_item_id', $item->id)
                    ->where('student_id', $siswa->id)
                    ->where('status', 'borrowed')
                    ->first() : null;

                $remainingDays = null;
                if ($activeLoan && $activeLoan->due_date) {
                    $remainingDays = max(0, now()->diffInDays($activeLoan->due_date, false));
                }

                return [
                    'id' => $item->id,
                    'judul' => $item->title,
                    'penulis' => $item->author,
                    'penerbit' => $item->penerbit,
                    'tahun_terbit' => $item->tahun_terbit,
                    'tipe' => $this->mapCategoryToType($item->category),
                    'cover_url' => $item->cover_image ? asset('storage/' . $item->cover_image) : null,
                    'durasi' => $item->durasi,
                    'jumlah_halaman' => $item->jumlah_halaman,
                    'kategori' => $item->kategori,
                    'is_borrowed' => $activeLoan !== null,
                    'remaining_days' => $remainingDays,
                ];
            });

        // Get unique categories for filter
        $categories = LibraryItem::withoutGlobalScope('school')
            ->where(function ($q) use ($user) {
                $q->where('school_id', $user->school_id)
                  ->orWhereNull('school_id');
            })
            ->where('category', $category)
            ->whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori');

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'categories' => $categories,
            ]
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();

        $siswa = Siswa::withoutGlobalScope('school')
            ->where('user_id', $user->id)
            ->first();

        $item = LibraryItem::withoutGlobalScope('school')
            ->where(function ($q) use ($user) {
                $q->where('school_id', $user->school_id)
                  ->orWhereNull('school_id');
            })
            ->findOrFail($id);

        $activeLoan = $siswa ? LibraryLoan::withoutGlobalScope('school')
            ->where('library_item_id', $item->id)
            ->where('student_id', $siswa->id)
            ->where('status', 'borrowed')
            ->first() : null;

        $borrowingInfo = null;
        if ($activeLoan) {
            $remainingDays = max(0, now()->diffInDays($activeLoan->due_date, false));
            $borrowingInfo = [
                'tanggal_pinjam' => $activeLoan->loan_date->format('d M Y'),
                'tanggal_kembali' => $activeLoan->due_date->format('d M Y'),
                'remaining_days' => $remainingDays,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $item->id,
                'judul' => $item->title,
                'deskripsi' => $item->description,
                'penulis' => $item->author,
                'penerbit' => $item->penerbit,
                'tahun_terbit' => $item->tahun_terbit,
                'tipe' => $this->mapCategoryToType($item->category),
                'cover_url' => $item->cover_image ? asset('storage/' . $item->cover_image) : null,
                'durasi' => $item->durasi,
                'jumlah_halaman' => $item->jumlah_halaman,
                'kategori' => $item->category,
                'is_borrowed' => $activeLoan !== null,
                'borrowing_info' => $borrowingInfo,
            ]
        ]);
    }

    public function borrow(Request $request, $id)
    {
        $request->validate([
            'durasi_hari' => 'required|integer|in:3,7,14,30',
        ]);

        $user = Auth::user();

        $siswa = Siswa::withoutGlobalScope('school')
            ->where('user_id', $user->id)
            ->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        $item = LibraryItem::withoutGlobalScope('school')
            ->where(function ($q) use ($user) {
                $q->where('school_id', $user->school_id)
                  ->orWhereNull('school_id');
            })
            ->findOrFail($id);

        // Check if already borrowed
        $existingLoan = LibraryLoan::withoutGlobalScope('school')
            ->where('library_item_id', $item->id)
            ->where('student_id', $siswa->id)
            ->where('status', 'borrowed')
            ->first();

        if ($existingLoan) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah meminjam item ini'
            ], 400);
        }

        // Check max concurrent borrowings (limit: 5)
        $activeBorrowingsCount = LibraryLoan::withoutGlobalScope('school')
            ->where('student_id', $siswa->id)
            ->where('status', 'borrowed')
            ->count();

        if ($activeBorrowingsCount >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mencapai batas maksimal peminjaman (5 item)'
            ], 400);
        }

        $loan = LibraryLoan::create([
            'school_id' => $siswa->school_id,
            'library_item_id' => $item->id,
            'student_id' => $siswa->id,
            'loan_date' => now()->toDateString(),
            'due_date' => now()->addDays($request->durasi_hari)->toDateString(),
            'status' => 'borrowed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil meminjam ' . $item->title,
            'data' => [
                'tanggal_pinjam' => $loan->loan_date->format('d M Y'),
                'tanggal_kembali' => $loan->due_date->format('d M Y'),
                'durasi_hari' => $request->durasi_hari,
            ]
        ]);
    }

    public function myBorrowings()
    {
        $user = Auth::user();

        $siswa = Siswa::withoutGlobalScope('school')
            ->where('user_id', $user->id)
            ->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        $loans = LibraryLoan::withoutGlobalScope('school')
            ->with(['item' => function ($q) {
                $q->withoutGlobalScope('school');
            }])
            ->where('student_id', $siswa->id)
            ->where('status', 'borrowed')
            ->orderBy('due_date', 'asc')
            ->get()
            ->map(function ($loan) {
                $isExpired = $loan->due_date < now()->toDateString();
                $remainingDays = $isExpired ? 0 : max(0, now()->diffInDays($loan->due_date, false));
                
                return [
                    'id' => $loan->id,
                    'item_id' => $loan->library_item_id,
                    'judul' => $loan->item?->title,
                    'tipe' => $this->mapCategoryToType($loan->item?->category),
                    'cover_url' => $loan->item?->cover_image ? asset('storage/' . $loan->item->cover_image) : null,
                    'tanggal_pinjam' => $loan->loan_date->format('d M Y'),
                    'tanggal_kembali' => $loan->due_date->format('d M Y'),
                    'remaining_days' => $remainingDays,
                    'is_expired' => $isExpired,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $loans
        ]);
    }

    public function read($id)
    {
        $user = Auth::user();

        $siswa = Siswa::withoutGlobalScope('school')
            ->where('user_id', $user->id)
            ->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        $item = LibraryItem::withoutGlobalScope('school')
            ->findOrFail($id);

        // Check if student has active loan
        $activeLoan = LibraryLoan::withoutGlobalScope('school')
            ->where('library_item_id', $item->id)
            ->where('student_id', $siswa->id)
            ->where('status', 'borrowed')
            ->first();

        if (!$activeLoan) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus meminjam item ini terlebih dahulu'
            ], 403);
        }

        $remainingDays = max(0, now()->diffInDays($activeLoan->due_date, false));

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $item->id,
                'judul' => $item->title,
                'penulis' => $item->author,
                'tipe' => $this->mapCategoryToType($item->category),
                'cover_url' => $item->cover_image ? asset('storage/' . $item->cover_image) : null,
                'file_url' => $item->file_path ? asset('storage/' . $item->file_path) : null,
                'durasi' => $item->durasi,
                'remaining_days' => $remainingDays,
            ]
        ]);
    }

    private function mapCategoryToType($category)
    {
        $map = [
            'book' => 'ebook',
            'audio' => 'audiobook',
            'video' => 'videobook',
        ];
        return $map[$category] ?? 'ebook';
    }
}
