<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContatoMail extends Mailable
{
    public function __construct(public array $dados) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address'),
            replyTo: $this->dados['email'],
            subject: 'Nova mensagem do site Fire Out — ' . now()->format('d/m/Y H:i'),
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.contato',   // resources/views/emails/contato.blade.php
        );
    }
}
