<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewAnnouncementNotification extends Notification
{
    use Queueable;

    protected $announcement;

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'announcement',
            'icon' => 'campaign',
            'color' => 'amber',
            'title' => 'Pengumuman Baru',
            'message' => $this->announcement->judul,
            'url' => route('pengumuman.read', $this->announcement->id),
            'announcement_id' => $this->announcement->id,
        ];
    }
}
