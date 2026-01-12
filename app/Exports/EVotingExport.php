<?php

namespace App\Exports;

use App\Models\Election;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EVotingExport implements FromView, ShouldAutoSize
{
    protected $electionId;

    public function __construct($electionId)
    {
        $this->electionId = $electionId;
    }

    public function view(): View
    {
        $election = Election::with(['candidates.student', 'votes'])->findOrFail($this->electionId);
        
        $results = $election->candidates->map(function ($c) {
            return [
                'nama' => $c->student->nama_lengkap,
                'no_urut' => $c->no_urut,
                'total_suara' => $c->votes()->count()
            ];
        });

        return view('admin.e-voting.export_excel', [
            'election' => $election,
            'results' => $results
        ]);
    }
}
