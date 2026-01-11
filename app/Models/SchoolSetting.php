<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SchoolSetting extends Model
{
    use HasUuids;

    protected $fillable = [
        'semester',
        'tahun_pelajaran',
        'jenjang',
        'is_active',
    ];
}
