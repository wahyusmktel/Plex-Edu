<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use Illuminate\Http\Request;

class BankSoalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Fetch only public bank soal (check for both variants)
        $bankSoal = BankSoal::with(['subject', 'teacher', 'questions'])
            ->whereIn('status', ['public', 'publik'])
            ->latest()
            ->get();

        return view('student.bank-soal.index', compact('bankSoal'));
    }

    public function show($id)
    {
        $bankSoal = BankSoal::with(['subject', 'teacher', 'questions.options'])
            ->whereIn('status', ['public', 'publik'])
            ->findOrFail($id);

        return view('student.bank-soal.show', compact('bankSoal'));
    }
}
