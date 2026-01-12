<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ElectionCandidate extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'election_id',
        'siswa_id',
        'no_urut',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function student()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function votes()
    {
        return $this->hasMany(ElectionVote::class, 'candidate_id');
    }
}
