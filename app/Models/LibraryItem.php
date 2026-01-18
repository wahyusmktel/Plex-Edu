<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryItem extends Model
{
    use HasFactory, HasUuids, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'title',       // existing column
        'author',      // existing column
        'description', // existing column
        'category',    // existing column
        'penerbit',
        'tahun_terbit',
        'tipe',
        'cover_image',
        'file_path',
        'durasi',
        'jumlah_halaman',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'durasi' => 'integer',
        'jumlah_halaman' => 'integer',
    ];

    public function borrowings()
    {
        return $this->hasMany(LibraryBorrowing::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('tipe', $type);
    }

    // Aliases for consistency with API
    public function getJudulAttribute()
    {
        return $this->title;
    }

    public function getPenulisAttribute()
    {
        return $this->author;
    }

    public function getDeskripsiAttribute()
    {
        return $this->description;
    }

    public function getKategoriAttribute()
    {
        return $this->category;
    }

    public function getCoverUrlAttribute()
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
}
