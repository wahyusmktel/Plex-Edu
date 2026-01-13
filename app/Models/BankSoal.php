<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    use HasUuids;

    protected $fillable = [
        'subject_id',
        'teacher_id',
        'title',
        'level',
        'status',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Fungsionaris::class, 'teacher_id');
    }

    public function questions()
    {
        return $this->hasMany(BankSoalQuestion::class);
    }
}
