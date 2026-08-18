<?php

namespace App\Console\Commands;

use App\Jobs\SendTransactionNotification;
use App\Models\Transaction;
use App\Models\TransactionNotification;
use Illuminate\Console\Command;

class CheckTransactionNotifications extends Command
{
    protected $signature = 'transactions:check-notifications';

    protected $description = 'Verifica contas próximas do vencimento e contas vencidas';

    public function handle(): int
    {
        $transactions = Transaction::where('status', '!=', 'paid_off')
            ->get();

        foreach ($transactions as $transaction) {

            $type = null;

            if ($transaction->due_date == today()->addDay()->toDateString()) {
                $type = 'due_soon';
            }

            if ($transaction->due_date < today()->toDateString()) {
                $type = 'overdue';
            }

            if (!$type) {
                continue;
            }

            $notification = TransactionNotification::firstOrCreate([
                'transaction_id' => $transaction->id,
                'type' => $type,
            ]);

            if ($notification->wasRecentlyCreated) {
                SendTransactionNotification::dispatch($notification);
            }
        }

        return self::SUCCESS;
    }
}