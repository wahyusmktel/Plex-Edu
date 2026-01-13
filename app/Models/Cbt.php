<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Cbt extends Model
{
    use HasUuids;

    protected $fillable = [
        'nama_cbt',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'subject_id',
        'token',
        'skor_maksimal',
        'show_result',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'show_result' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->token = static::generateUniqueToken();
        });
    }

    public static function generateUniqueToken()
    {
        do {
            // 5 combination of letters and numbers, uppercase, avoid O and I
            $pool = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            $token = substr(str_shuffle(str_repeat($pool, 5)), 0, 5);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function questions()
    {
        return $this->hasMany(CbtQuestion::class);
    }

    public function sessions()
    {
        return $this->hasMany(CbtSession::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
