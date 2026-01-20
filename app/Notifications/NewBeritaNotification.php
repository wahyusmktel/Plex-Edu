<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBeritaNotification extends Notification
{
    use Queueable;

    protected $berita;

    public function __construct($berita)
    {
        $this->berita = $berita;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'berita',
            'icon' => 'article',
            'color' => 'blue',
            'title' => 'Berita Terbaru',
            'message' => $this->berita->judul,
            'url' => route('berita.read', $this->berita->id),
            'berita_id' => $this->berita->id,
        ];
    }
}
