<?php

namespace App\Mail;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $emailSubject,
        public string $notificationMessage,
        public string $type,
        public User $user,
        public ?string $link = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Relief] ' . $this->emailSubject,
        );
    }

    public function content(): Content
    {
        // Build a notification-like object for the template
        $notification = (object) [
            'type' => $this->type,
            'title' => $this->emailSubject,
            'message' => $this->notificationMessage,
            'link' => $this->link,
            'created_at' => now(),
        ];

        return new Content(
            view: 'emails.notification',
            with: [
                'notification' => $notification,
                'notificationMessage' => $this->notificationMessage,
                'type' => $this->type,
                'color' => Notification::getColor($this->type),
                'userName' => $this->user->name,
            ],
        );
    }
}
