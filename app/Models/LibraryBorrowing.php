<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryBorrowing extends Model
{
    use HasFactory, HasUuids, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'library_item_id',
        'siswa_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'durasi_hari',
        'status',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'durasi_hari' => 'integer',
    ];

    public function libraryItem()
    {
        return $this->belongsTo(LibraryItem::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForStudent($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && $this->tanggal_kembali >= now()->toDateString();
    }

    public function getRemainingDaysAttribute()
    {
        if (!$this->is_active) return 0;
        return now()->diffInDays($this->tanggal_kembali, false);
    }
}
