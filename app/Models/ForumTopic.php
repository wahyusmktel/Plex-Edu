<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumTopic extends Model
{
    use HasFactory, HasUuids, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'forum_id',
        'user_id',
        'title',
        'content',
        'is_pinned',
        'is_locked',
        'status',
    ];

    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(ForumPost::class, 'topic_id');
    }

    public function bookmarks()
    {
        return $this->hasMany(ForumBookmark::class, 'topic_id');
    }

    public function mutes()
    {
        return $this->hasMany(ForumMute::class, 'topic_id');
    }
}
