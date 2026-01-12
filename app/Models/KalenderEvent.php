<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class KalenderEvent extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'category',
        'start_date',
        'end_date',
        'description',
        'color',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
