<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ELearningChapter extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'e_learning_chapters';

    protected $fillable = [
        'e_learning_id',
        'title',
        'order',
    ];

    public function course()
    {
        return $this->belongsTo(ELearning::class, 'e_learning_id');
    }

    public function modules()
    {
        return $this->hasMany(ELearningModule::class, 'chapter_id')->orderBy('order');
    }
}
