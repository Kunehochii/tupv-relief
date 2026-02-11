<?php

namespace App\Mail;

use App\Models\Pledge;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PledgeSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pledge $pledge
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Relief] Pledge Received - ' . $this->pledge->reference_number,
        );
    }

    public function content(): Content
    {
        $this->pledge->load(['drive', 'pledgeItems', 'user']);

        return new Content(
            view: 'emails.pledge-submitted',
            with: [
                'pledge' => $this->pledge,
                'user' => $this->pledge->user,
                'drive' => $this->pledge->drive,
                'items' => $this->pledge->pledgeItems,
            ],
        );
    }
}
