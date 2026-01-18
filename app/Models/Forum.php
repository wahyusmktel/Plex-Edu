<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory, HasUuids, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'created_by',
        'title',
        'description',
        'is_active',
        'visibility',
        'class_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function topics()
    {
        return $this->hasMany(ForumTopic::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    public function allowedSchools()
    {
        return $this->belongsToMany(School::class, 'forum_allowed_schools', 'forum_id', 'school_id');
    }
}
