<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\ElectionCandidate;
use App\Models\ElectionVote;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EVotingApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $elections = Election::withoutGlobalScope('school')
            ->with(['candidates.student' => function($q) {
                $q->withoutGlobalScope('school');
            }])
            ->where('school_id', $user->school_id)
            ->where('is_active', true)
            ->orderBy('end_date', 'desc')
            ->get()
            ->map(function ($election) use ($user) {
                $hasVoted = ElectionVote::where('election_id', $election->id)
                    ->where('voter_id', $user->id)
                    ->exists();

                $now = now();
                $status = 'upcoming';
                if ($now->between($election->start_date, $election->end_date)) {
                    $status = 'ongoing';
                } elseif ($now->gte($election->end_date)) {
                    $status = 'ended';
                }

                return [
                    'id' => $election->id,
                    'judul' => $election->judul,
                    'jenis' => $election->jenis,
                    'start_date' => $election->start_date->format('d M Y H:i'),
                    'end_date' => $election->end_date->format('d M Y H:i'),
                    'status' => $status,
                    'has_voted' => $hasVoted,
                    'candidates_count' => $election->candidates->count(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $elections
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();

        $election = Election::withoutGlobalScope('school')
            ->with(['candidates.student' => function($q) {
                $q->withoutGlobalScope('school');
            }])
            ->where('school_id', $user->school_id)
            ->findOrFail($id);

        $hasVoted = ElectionVote::where('election_id', $election->id)
            ->where('voter_id', $user->id)
            ->exists();

        $votedCandidateId = null;
        if ($hasVoted) {
            $vote = ElectionVote::where('election_id', $election->id)
                ->where('voter_id', $user->id)
                ->first();
            $votedCandidateId = $vote?->candidate_id;
        }

        $now = now();
        $status = 'upcoming';
        if ($now->between($election->start_date, $election->end_date)) {
            $status = 'ongoing';
        } elseif ($now->gte($election->end_date)) {
            $status = 'ended';
        }

        $candidates = $election->candidates->map(function ($candidate) use ($status) {
            $data = [
                'id' => $candidate->id,
                'no_urut' => $candidate->no_urut,
                'nama' => $candidate->student?->nama_lengkap ?? 'N/A',
                'kelas' => $candidate->student?->kelas?->nama ?? 'N/A',
                'foto' => $candidate->student?->foto ? asset('storage/' . $candidate->student->foto) : null,
            ];

            // Only show vote counts if election has ended
            if ($status === 'ended') {
                $data['votes'] = $candidate->votes()->count();
            }

            return $data;
        })->sortBy('no_urut')->values();

        return response()->json([
            'success' => true,
            'data' => [
                'election' => [
                    'id' => $election->id,
                    'judul' => $election->judul,
                    'jenis' => $election->jenis,
                    'start_date' => $election->start_date->format('d M Y H:i'),
                    'end_date' => $election->end_date->format('d M Y H:i'),
                    'status' => $status,
                ],
                'has_voted' => $hasVoted,
                'voted_candidate_id' => $votedCandidateId,
                'candidates' => $candidates
            ]
        ]);
    }

    public function vote(Request $request, $id)
    {
        $request->validate([
            'candidate_id' => 'required|exists:election_candidates,id',
        ]);

        $user = Auth::user();

        $election = Election::withoutGlobalScope('school')
            ->where('school_id', $user->school_id)
            ->findOrFail($id);

        $now = now();
        if (!$now->between($election->start_date, $election->end_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Pemilihan tidak dalam periode voting.'
            ], 400);
        }

        // Check if already voted
        $alreadyVoted = ElectionVote::where('election_id', $election->id)
            ->where('voter_id', $user->id)
            ->exists();

        if ($alreadyVoted) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memberikan suara di pemilihan ini.'
            ], 400);
        }

        // Validate candidate belongs to this election
        $candidate = ElectionCandidate::where('id', $request->candidate_id)
            ->where('election_id', $election->id)
            ->first();

        if (!$candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Kandidat tidak valid.'
            ], 400);
        }

        ElectionVote::create([
            'election_id' => $election->id,
            'candidate_id' => $request->candidate_id,
            'voter_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Suara Anda berhasil dicatat. Terima kasih telah berpartisipasi!'
        ]);
    }
}
