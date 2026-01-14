<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SchoolSetting extends Model
{
    use HasUuids, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'semester',
        'tahun_pelajaran',
        'jenjang',
        'is_active',
    ];
}
