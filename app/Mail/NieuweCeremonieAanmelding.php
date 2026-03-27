<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NieuweCeremonieAanmelding extends Mailable
{
    use Queueable, SerializesModels;

    public $deelnemer;
    public $ceremonie;

    /**
     * Create a new message instance.
     */
    public function __construct($deelnemer, $ceremonie)
    {
        $this->deelnemer = $deelnemer;
        $this->ceremonie = $ceremonie;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $naam = trim(($this->deelnemer->voornaam ?? '') . ' ' . ($this->deelnemer->tussenvoegsel ?? '') . ' ' . ($this->deelnemer->achternaam ?? ''));
        return new Envelope(
            subject: 'Nieuwe Ceremonie Aanmelding: ' . $naam,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.nieuwe_ceremonie_aanmelding',
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
