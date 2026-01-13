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
        'participant_type',
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

    public function allowedKelas()
    {
        return $this->belongsToMany(Kelas::class, 'cbt_kelas', 'cbt_id', 'kelas_id')->withTimestamps();
    }

    public function allowedSiswas()
    {
        return $this->belongsToMany(Siswa::class, 'cbt_siswa', 'cbt_id', 'siswa_id')->withTimestamps();
    }

    public function canParticipate(Siswa $siswa): bool
    {
        if ($this->participant_type === 'all') {
            return true;
        }

        if ($this->participant_type === 'kelas') {
            return $this->allowedKelas()->where('kelas.id', $siswa->kelas_id)->exists();
        }

        if ($this->participant_type === 'siswa') {
            return $this->allowedSiswas()->where('siswas.id', $siswa->id)->exists();
        }

        return false;
    }

    public function getStatusAttribute(): string
    {
        $now = now();
        $startTime = \Carbon\Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . $this->jam_mulai);
        $endTime = \Carbon\Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . $this->jam_selesai);

        if ($now->lt($startTime)) {
            return 'upcoming';
        } elseif ($now->gt($endTime)) {
            return 'completed';
        } else {
            return 'ongoing';
        }
    }
}

