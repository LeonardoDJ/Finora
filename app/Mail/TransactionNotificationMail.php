<?php

namespace App\Mail;

use App\Models\TransactionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TransactionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TransactionNotification $notification
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->notification->type === 'due_soon'
            ? 'Conta próxima do vencimento'
            : 'Conta vencida';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}