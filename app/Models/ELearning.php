<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ELearning extends Model
{
    use HasFactory, HasUuids, \App\Traits\BelongsToSchool;

    protected $table = 'e_learnings';

    protected $fillable = [
        'school_id',
        'subject_id',
        'teacher_id',
        'title',
        'description',
        'thumbnail',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Fungsionaris::class, 'teacher_id');
    }

    public function chapters()
    {
        return $this->hasMany(ELearningChapter::class, 'e_learning_id')->orderBy('order');
    }
}
