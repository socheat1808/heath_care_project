<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Update — One Health',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.appointments.rejected',
            with: [
                'appointment'      => $this->appointment,
                'patient'          => $this->appointment->patient,
                'doctor'           => $this->appointment->doctor,
                'rejection_reason' => $this->appointment->rejection_reason,
            ]
        );
    }
}
