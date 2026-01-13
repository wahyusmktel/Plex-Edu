<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CbtAnswer extends Model
{
    use HasUuids;

    protected $fillable = [
        'session_id',
        'question_id',
        'option_id',
        'essay_answer',
        'poin_didapat',
        'is_graded',
    ];

    protected $casts = [
        'is_graded' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(CbtSession::class, 'session_id');
    }

    public function question()
    {
        return $this->belongsTo(CbtQuestion::class, 'question_id');
    }

    public function option()
    {
        return $this->belongsTo(CbtOption::class, 'option_id');
    }
}
