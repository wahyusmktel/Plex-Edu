<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Berita extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'thumbnail',
        'tanggal_terbit',
        'jam_terbit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
