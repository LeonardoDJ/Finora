<?php

namespace App\Jobs;

use App\Models\TransactionNotification;
use App\Mail\TransactionNotificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendTransactionNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TransactionNotification $notification
    ) {}

    public function handle(): void
    {
        $transaction = $this->notification->transaction;

        Mail::to($transaction->user->email)
            ->send(new TransactionNotificationMail($this->notification));

        $this->notification->update([
            'sent_at' => now(),
        ]);
    }
}