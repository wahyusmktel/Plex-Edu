<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ELearningGradedNotification extends Notification
{
    use Queueable;

    protected $submission;

    public function __construct($submission)
    {
        $this->submission = $submission;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'elearning_graded',
            'icon' => 'grade',
            'color' => 'yellow',
            'title' => 'Tugas Dinilai',
            'message' => 'Tugas Anda telah dinilai: ' . ($this->submission->eLearning->judul ?? 'Tugas'),
            'url' => route('elearning.show', $this->submission->e_learning_id),
            'submission_id' => $this->submission->id,
        ];
    }
}
