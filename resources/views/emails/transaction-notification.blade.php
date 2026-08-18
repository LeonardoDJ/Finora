<h1>Aviso financeiro</h1>

<p>
    @if ($notification->type === 'due_soon')
        Uma conta está próxima do vencimento.
    @else
        Uma conta venceu e ainda não foi quitada.
    @endif
</p>

<p>
    Valor: R$ {{ $notification->transaction->amount }}
</p>

<p>
    Vencimento: {{ $notification->transaction->due_date }}
</p>

<p>
    Contato: {{ $notification->transaction->contact?->email }}
</p>