<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Sambutan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'judul',
        'thumbnail',
        'konten',
        'is_active',
    ];
}
