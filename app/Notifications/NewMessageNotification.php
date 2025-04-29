<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $body;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title = "New Message", string $body = "You received a new message.")
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['fcm'];
    }

    /**
     * Build the FCM notification.
     */
    public function toFcm(object $notifiable): FcmMessage
    {
        return FcmMessage::create()
            ->setNotification(
                FcmNotification::create()
                    ->setTitle($this->title)
                    ->setBody($this->body)
                    ->setImage(null) // optional
            )
            ->setData([
                'type' => 'chat',
                'message' => $this->body,
            ]);
    }
}
