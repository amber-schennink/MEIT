<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AanmeldingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
      public object $training,
      public object $deelnemer,
      public object $aanmelding,
      public string $type // 'wachtlijst' | 'betaling'
    ) {}

    public function build()
    {
      return $this->subject('Nieuwe aanmelding')
        ->view('emails.aanmelding-notification');
    }
}
