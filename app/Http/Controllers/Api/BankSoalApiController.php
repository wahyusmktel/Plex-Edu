<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankSoalApiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $bankSoals = BankSoal::withoutGlobalScope('school')
            ->with([
                'subject' => function($q) {
                    $q->withoutGlobalScope('school');
                },
                'teacher' => function($q) {
                    $q->withoutGlobalScope('school');
                }
            ])
            ->where('status', 'public')
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'level' => $item->level,
                    'subject' => $item->subject?->nama_pelajaran ?? 'N/A',
                    'teacher' => $item->teacher?->nama ?? 'N/A',
                    'questions_count' => $item->questions()->count(),
                    'created_at' => $item->created_at->format('d M Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $bankSoals
        ]);
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
                'questions.options'
            ])
            ->where('status', 'public')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $bankSoal->id,
                'title' => $bankSoal->title,
                'level' => $bankSoal->level,
                'subject' => $bankSoal->subject?->nama_pelajaran ?? 'N/A',
                'teacher' => $bankSoal->teacher?->nama ?? 'N/A',
                'questions_count' => $bankSoal->questions->count(),
                'created_at' => $bankSoal->created_at->format('d M Y'),
                'questions' => $bankSoal->questions->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'type' => $q->jenis_soal,
                        'question' => $q->pertanyaan,
                        'image' => $q->gambar ? asset('storage/' . $q->gambar) : null,
                        'points' => $q->poin,
                        'options' => $q->options->map(function ($o) {
                            return [
                                'id' => $o->id,
                                'text' => $o->opsi,
                                'is_correct' => (bool)$o->is_correct
                            ];
                        })
                    ];
                })
            ]
        ]);
    }
}
