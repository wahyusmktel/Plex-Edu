<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ELearningProgress extends Model
{
    use HasUuids;

    protected $table = 'e_learning_progress';

    protected $fillable = [
        'siswa_id',
        'module_id',
        'completed_at',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function module()
    {
        return $this->belongsTo(ELearningModule::class, 'module_id');
    }
}
