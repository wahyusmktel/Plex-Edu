<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ElectionVote extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'election_id',
        'candidate_id',
        'voter_id',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function candidate()
    {
        return $this->belongsTo(ElectionCandidate::class);
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }
}
