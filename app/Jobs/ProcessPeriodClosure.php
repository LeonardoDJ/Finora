<?php

namespace App\Jobs;

use App\Models\PeriodClosure;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use App\Mail\PeriodClosureMail;
use Illuminate\Support\Facades\Mail;

class ProcessPeriodClosure implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PeriodClosure $periodClosure
    ) {}

    public function handle(): void{
        $this->periodClosure->update([
            'status' => 'processing',
        ]);

        $transactions = Transaction::where('user_id', $this->periodClosure->user_id)
            ->whereDate('due_date', '>=', $this->periodClosure->start_date)
            ->whereDate('due_date', '<=', $this->periodClosure->end_date)
            ->with('contact')
            ->get();

        $totalReceive = $transactions
            ->where('type', 'receive')
            ->sum('amount');

        $totalPay = $transactions
            ->where('type', 'pay')
            ->sum('amount');

        $balance = $totalReceive - $totalPay;

        $fileName = 'fechamento_' .
            $this->periodClosure->user_id . '_' .
            $this->periodClosure->start_date . '_' .
            $this->periodClosure->end_date . '.txt';

        $content = "FECHAMENTO FINANCEIRO\n";
        $content .= "Período: {$this->periodClosure->start_date} até {$this->periodClosure->end_date}\n\n";

        $content .= "Total a receber: R$ {$totalReceive}\n";
        $content .= "Total a pagar: R$ {$totalPay}\n";
        $content .= "Saldo: R$ {$balance}\n\n";

        $content .= "LANÇAMENTOS\n";
        $content .= "-----------------------------\n";

        foreach ($transactions as $transaction) {
            $content .= "Tipo: {$transaction->type}\n";
            $content .= "Valor: R$ {$transaction->amount}\n";
            $content .= "Vencimento: {$transaction->due_date}\n";
            $content .= "Status: {$transaction->status}\n";
            $content .= "Contato: " . ($transaction->contact?->email ?? 'Sem contato') . "\n";
            $content .= "-----------------------------\n";
        }

        Storage::disk('local')->put($fileName, $content);

        $this->periodClosure->update([
            'file_path' => $fileName,
        ]);

        Mail::to($this->periodClosure->user->email)
            ->send(new PeriodClosureMail($this->periodClosure));

        $this->periodClosure->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function failed(\Throwable $exception): void{
        $this->periodClosure->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
    }
}