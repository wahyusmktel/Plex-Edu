<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CbtQuestion extends Model
{
    use HasUuids;

    protected $fillable = [
        'cbt_id',
        'jenis_soal',
        'pertanyaan',
        'gambar',
        'poin',
    ];

    public function cbt()
    {
        return $this->belongsTo(Cbt::class);
    }

    public function options()
    {
        return $this->hasMany(CbtOption::class, 'question_id');
    }

    public function answers()
    {
        return $this->hasMany(CbtAnswer::class, 'question_id');
    }
}
