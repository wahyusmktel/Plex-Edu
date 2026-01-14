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
        'title',
        'author',
        'description',
        'category',
        'file_path',
        'cover_image',
    ];

    public function loans()
    {
        return $this->hasMany(LibraryLoan::class);
    }
}
