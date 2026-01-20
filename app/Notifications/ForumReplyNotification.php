<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ForumReplyNotification extends Notification
{
    use Queueable;

    protected $reply;
    protected $topic;

    public function __construct($reply, $topic)
    {
        $this->reply = $reply;
        $this->topic = $topic;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'forum_reply',
            'icon' => 'forum',
            'color' => 'purple',
            'title' => 'Balasan Forum Baru',
            'message' => 'Ada balasan baru di topik "' . $this->topic->title . '"',
            'url' => route('forum.show', $this->topic->id),
            'topic_id' => $this->topic->id,
            'reply_id' => $this->reply->id,
        ];
    }
}
