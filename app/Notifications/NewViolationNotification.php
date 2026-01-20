<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewViolationNotification extends Notification
{
    use Queueable;

    protected $violation;

    public function __construct($violation)
    {
        $this->violation = $violation;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'violation',
            'icon' => 'gavel',
            'color' => 'red',
            'title' => 'Pelanggaran Tercatat',
            'message' => 'Pelanggaran baru: ' . ($this->violation->masterPelanggaran->nama ?? 'Pelanggaran'),
            'url' => route('student.pelanggaran.index'),
            'violation_id' => $this->violation->id,
        ];
    }
}
