<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewForumTopicNotification extends Notification
{
    use Queueable;

    protected $topic;

    public function __construct($topic)
    {
        $this->topic = $topic;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'forum_topic',
            'icon' => 'add_comment',
            'color' => 'blue',
            'title' => 'Topik Diskusi Baru',
            'message' => 'Topik baru: "' . $this->topic->title . '"',
            'url' => route('forum.show', $this->topic->id),
            'topic_id' => $this->topic->id,
        ];
    }
}
