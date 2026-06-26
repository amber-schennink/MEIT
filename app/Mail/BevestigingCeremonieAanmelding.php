<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BevestigingCeremonieAanmelding extends Mailable
{
    use Queueable, SerializesModels;

    public $deelnemer;
    public $ceremonie;
    public $duo_deelnemer;

    /**
     * Create a new message instance.
     */
    public function __construct($deelnemer, $ceremonie, $duo_deelnemer_mail)
    {
        $this->deelnemer = $deelnemer;
        $this->duo_deelnemer = $duo_deelnemer_mail;
        $this->ceremonie = $ceremonie;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bevestiging van jouw Tepezcohuite ceremonie ♡',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bevestiging_ceremonie_aanmelding',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
