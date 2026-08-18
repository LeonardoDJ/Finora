<?php

namespace App\Mail;

use App\Models\PeriodClosure;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

class PeriodClosureMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PeriodClosure $periodClosure
    ) {}

    public function envelope(): Envelope{
        return new Envelope(
            subject: 'Fechamento financeiro do período',
        );
    }

    public function content(): Content{
        return new Content(
            view: 'emails.period-closure',
        );
    }

    public function attachments(): array{
        return [
            Attachment::fromStorageDisk(
                'local',
                $this->periodClosure->file_path
            ),
        ];
    }
}