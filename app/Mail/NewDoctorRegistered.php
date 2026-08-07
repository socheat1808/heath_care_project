<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewDoctorRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $specialization
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 New Doctor Registration — Action Required',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.new-doctor-registered',
            with: [
                'user'           => $this->user,
                'specialization' => $this->specialization,
            ]
        );
    }
}
