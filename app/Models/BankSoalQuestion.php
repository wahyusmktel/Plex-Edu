<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BankSoalQuestion extends Model
{
    use HasUuids;

    protected $fillable = [
        'bank_soal_id',
        'jenis_soal',
        'pertanyaan',
        'gambar',
        'poin',
    ];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function options()
    {
        return $this->hasMany(BankSoalOption::class, 'bank_soal_question_id');
    }
}
