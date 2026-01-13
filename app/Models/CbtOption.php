<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CbtOption extends Model
{
    use HasUuids;

    protected $fillable = [
        'question_id',
        'opsi',
        'gambar',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(CbtQuestion::class, 'question_id');
    }
}
