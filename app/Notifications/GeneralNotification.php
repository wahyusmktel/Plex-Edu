<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->data['type'] ?? 'general',
            'icon' => $this->data['icon'] ?? 'notifications',
            'color' => $this->data['color'] ?? 'blue',
            'title' => $this->data['title'] ?? 'Pemberitahuan',
            'message' => $this->data['message'] ?? '',
            'url' => $this->data['url'] ?? '#',
            'action_type' => $this->data['action_type'] ?? null,
            'action_id' => $this->data['action_id'] ?? null,
        ];
    }
}
