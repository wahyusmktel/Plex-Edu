<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pengumuman extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pengumumans';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tanggal_terbit',
        'tanggal_berakhir',
        'is_permanen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
