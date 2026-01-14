<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Subject extends Model
{
    use HasUuids, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'kode_pelajaran',
        'nama_pelajaran',
        'guru_id',
        'is_active',
    ];

    public function guru()
    {
        return $this->belongsTo(Fungsionaris::class, 'guru_id');
    }
}
