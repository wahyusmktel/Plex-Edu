<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ELearningSubmission extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'e_learning_submissions';

    protected $fillable = [
        'module_id',
        'siswa_id',
        'content',
        'file_path',
        'score',
        'feedback',
    ];

    public function module()
    {
        return $this->belongsTo(ELearningModule::class, 'module_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
