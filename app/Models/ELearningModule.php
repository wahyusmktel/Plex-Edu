<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ELearningModule extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'e_learning_modules';

    protected $fillable = [
        'chapter_id',
        'type',
        'title',
        'content',
        'file_path',
        'cbt_id',
        'due_date',
        'order',
    ];

    public function chapter()
    {
        return $this->belongsTo(ELearningChapter::class, 'chapter_id');
    }

    public function cbt()
    {
        return $this->belongsTo(Cbt::class);
    }
}
