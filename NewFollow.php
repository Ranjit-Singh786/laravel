<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Follow;

class NewFollow extends Notification
{
    use Queueable;

    protected $follow;

    public function __construct(Follow $follow) {
        $this->follow = $follow;
    }

    public function via($notifiable) {
        return ['database'];
    }

    public function toArray($notifiable) {
        return [
            'follower_id' => $this->follow->user_id
        ];
    }
}
