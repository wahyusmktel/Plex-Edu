<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Schedule extends Model
{
    use HasUuids, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'id',
        'kelas_id',
        'subject_id',
        'jam_id',
        'school_setting_id',
        'hari',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function jam()
    {
        return $this->belongsTo(JamPelajaran::class, 'jam_id');
    }

    public function schoolSetting()
    {
        return $this->belongsTo(SchoolSetting::class);
    }
}
