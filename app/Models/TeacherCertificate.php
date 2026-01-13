<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TeacherCertificate extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'teacher_id',
        'name',
        'description',
        'year',
        'expiry_type',
        'expiry_date',
        'expiry_year',
        'file_path',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function teacher()
    {
        return $this->belongsTo(Fungsionaris::class, 'teacher_id');
    }
}
