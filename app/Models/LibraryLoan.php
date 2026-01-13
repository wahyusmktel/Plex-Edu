<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryLoan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'student_id',
        'library_item_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Siswa::class, 'student_id');
    }

    public function item()
    {
        return $this->belongsTo(LibraryItem::class, 'library_item_id');
    }
}
