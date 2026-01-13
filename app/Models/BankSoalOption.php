<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BankSoalOption extends Model
{
    use HasUuids;

    protected $fillable = [
        'bank_soal_question_id',
        'opsi',
        'gambar',
        'is_correct',
    ];

    public function question()
    {
        return $this->belongsTo(BankSoalQuestion::class, 'bank_soal_question_id');
    }
}
