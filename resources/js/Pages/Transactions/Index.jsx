import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout from '../../Components/AppLayout';

function Index({ transactions, contacts, summary, periodClosures, success }) {
    const [contactId, setContactId] = useState('');
    const [type, setType] = useState('');
    const [amount, setAmount] = useState('');
    const [dueDate, setDueDate] = useState('');
    const [startDate, setStartDate] = useState('');
    const [endDate, setEndDate] = useState('');

    const handleFilter = () => {
        router.get( '/lancamentos', {
                start_date: startDate,
                end_date: endDate,
            },
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        router.post('/lancamentos', {
            contact_id: contactId,
            type: type,
            amount: amount,
            due_date: dueDate,
        });
    };

    const handleClosure = () => {
        if (!startDate || !endDate) {
            alert('Selecione a data inicial e a data final.');
            return;
        }

        router.post(
            '/fechamentos',
            {
                start_date: startDate,
                end_date: endDate,
            },
            {
                onSuccess: () => {
                    alert('Fechamento solicitado com sucesso!');
                },

                onError: (errors) => {
                    console.log(errors);
                    alert(JSON.stringify(errors));
                },
            }
        );
    };

    return (
        <AppLayout>
            <div className="mb-8">
                <h1 className="text-3xl font-bold tracking-tight text-slate-900">
                    Lançamentos
                </h1>

                <p className="mt-1 text-slate-500">
                    Gerencie suas contas a pagar e receber.
                </p>
            </div>

            {success && (
            <div className="mb-6 flex items-center gap-3 rounded-xl px-5 py-4" style={{ backgroundColor: '#ecfdf5', border: '1px solid #a7f3d0', borderLeft: '5px solid #059669',}}>
                <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full font-bold" style={{ backgroundColor: '#d1fae5', color: '#059669', }} >
                    ✓
                </div>

                <div>
                    <p className="font-semibold" style={{ color: '#065f46' }} >
                        Operação realizada com sucesso
                    </p>

                    <p className="mt-1 text-sm" style={{ color: '#047857' }} >
                        {success}
                    </p>
                </div>
            </div>
        )}

            <div className="mb-8">
                <h2 className="mb-4 text-lg font-semibold text-slate-800">
                    Resumo financeiro
                </h2>

                <div
                    style={{
                        display: 'grid',
                        gridTemplateColumns: 'repeat(4, minmax(0, 1fr))',
                        gap: '16px',
                        width: '100%',
                    }}
                >
                    <div className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p className="text-sm font-medium text-slate-500">A receber</p>
                        <p className="mt-2 text-2xl font-bold text-emerald-600">
                            R$ {summary.total_receive}
                        </p>
                    </div>

                    <div className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p className="text-sm font-medium text-slate-500">A pagar</p>
                        <p className="mt-2 text-2xl font-bold text-slate-900">
                            R$ {summary.total_pay}
                        </p>
                    </div>

                    <div className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p className="text-sm font-medium text-slate-500">Liquidado</p>
                        <p className="mt-2 text-2xl font-bold text-blue-600">
                            R$ {summary.total_paid}
                        </p>
                    </div>

                    <div className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p className="text-sm font-medium text-slate-500">Vencido</p>
                        <p className="mt-2 text-2xl font-bold text-red-600">
                            R$ {summary.total_overdue}
                        </p>
                    </div>
                </div>
            </div>


            <div className="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div className="mb-4">
                    <h2 className="text-lg font-semibold text-slate-800">
                        Filtrar por período
                    </h2>

                    <p className="mt-1 text-sm text-slate-500">
                        Escolha um intervalo para visualizar ou fechar o período financeiro.
                    </p>
                </div>

                <div
                    style={{
                        display: 'grid',
                        gridTemplateColumns: '1fr 1fr auto auto',
                        gap: '12px',
                        alignItems: 'end',
                    }}
                >
                    <div>
                        <label className="mb-1 block text-sm font-medium text-slate-600">
                            Data inicial
                        </label>

                        <input
                            type="date"
                            value={startDate}
                            onChange={(e) => setStartDate(e.target.value)}
                            className="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none transition focus:border-slate-900"
                        />
                    </div>

                    <div>
                        <label className="mb-1 block text-sm font-medium text-slate-600">
                            Data final
                        </label>

                        <input
                            type="date"
                            value={endDate}
                            onChange={(e) => setEndDate(e.target.value)}
                            className="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none transition focus:border-slate-900"
                        />
                    </div>

                    <button
                        onClick={handleFilter}
                        className="rounded-xl border border-slate-300 bg-white px-5 py-2.5 font-medium text-slate-700 transition hover:bg-slate-100"
                    >
                        Filtrar
                    </button>

                    <button
                        onClick={handleClosure}
                        className="rounded-xl bg-slate-900 px-5 py-2.5 font-semibold text-white transition hover:bg-slate-700 active:scale-95"
                    >
                        Fechar período
                    </button>
                </div>
            </div>


            <div className="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div className="mb-4 flex items-center justify-between">
                    <div>
                        <h2 className="text-lg font-semibold text-slate-800">
                            Fechamentos solicitados
                        </h2>

                        <p className="mt-1 text-sm text-slate-500">
                            Acompanhe o status dos fechamentos enviados por e-mail.
                        </p>
                    </div>

                    <span className="rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-600">
                        {periodClosures.length} fechamento(s)
                    </span>
                </div>

                <div className="space-y-3">
                    {periodClosures.length === 0 && (
                        <div className="rounded-xl border border-dashed border-slate-300 p-6 text-center">
                            <p className="text-sm text-slate-500">
                                Nenhum fechamento solicitado até o momento.
                            </p>
                        </div>
                    )}

                    {periodClosures.map((closure) => (
                        <div
                            key={closure.id}
                            className="flex items-center justify-between rounded-xl border border-slate-200 p-4"
                        >
                            <div>
                                <p className="font-medium text-slate-800">
                                    {closure.start_date} até {closure.end_date}
                                </p>

                                {closure.status === 'failed' && (
                                    <p className="mt-1 text-sm text-red-600">
                                        O envio falhou. Você pode solicitar esse período novamente.
                                    </p>
                                )}
                            </div>

                            <span
                                className={`rounded-full px-3 py-1 text-xs font-semibold ${
                                    closure.status === 'sent'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : closure.status === 'failed'
                                        ? 'bg-red-100 text-red-700'
                                        : closure.status === 'processing'
                                        ? 'bg-blue-100 text-blue-700'
                                        : 'bg-amber-100 text-amber-700'
                                }`}
                            >
                                {closure.status === 'sent'
                                    ? 'Enviado'
                                    : closure.status === 'failed'
                                    ? 'Falhou'
                                    : closure.status === 'processing'
                                    ? 'Processando'
                                    : 'Pendente'}
                            </span>
                        </div>
                    ))}
                </div>
            </div>



            <form onSubmit={handleSubmit} className="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div className="mb-4">
                    <h2 className="text-lg font-semibold text-slate-800">
                        Novo lançamento
                    </h2>

                    <p className="mt-1 text-sm text-slate-500">
                        Cadastre uma conta a pagar ou a receber.
                    </p>
                </div>

                <div
                    style={{
                        display: 'grid',
                        gridTemplateColumns: '2fr 1fr 1fr 1fr auto',
                        gap: '12px',
                        alignItems: 'end',
                    }}
                >
                    <div>
                        <label className="mb-1 block text-sm font-medium text-slate-600">
                            Contato
                        </label>

                        <select value={contactId} onChange={(e) => setContactId(e.target.value)} className="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none transition focus:border-slate-900" >
                            <option value="">Selecione um contato</option>

                            {contacts.map((contact) => (
                                <option key={contact.id} value={contact.id}>
                                    {contact.email} - {contact.type}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label className="mb-1 block text-sm font-medium text-slate-600">
                            Tipo
                        </label>

                        <select value={type} onChange={(e) => setType(e.target.value)} className="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none transition focus:border-slate-900" >
                            <option value="">Selecione</option>
                            <option value="pay">A pagar</option>
                            <option value="receive">A receber</option>
                        </select>
                    </div>

                    <div>
                        <label className="mb-1 block text-sm font-medium text-slate-600">
                            Valor
                        </label>

                        <input type="number" step="0.01" placeholder="0,00" value={amount} onChange={(e) => setAmount(e.target.value)}
                            className="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none transition focus:border-slate-900"/>
                    </div>

                    <div>
                        <label className="mb-1 block text-sm font-medium text-slate-600">
                            Vencimento
                        </label>

                        <input type="date" value={dueDate} onChange={(e) => setDueDate(e.target.value)} className="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none transition focus:border-slate-900"
                        />
                    </div>

                    <button type="submit" className="rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white transition hover:bg-emerald-500 active:scale-95"
                    >
                        Cadastrar
                    </button>
                </div>
            </form>

            <div className="mb-8">
                <div className="mb-4 flex items-center justify-between">
                    <div>
                        <h2 className="text-lg font-semibold text-slate-800">
                            Meus lançamentos
                        </h2>

                        <p className="mt-1 text-sm text-slate-500">
                            Acompanhe suas contas a pagar e receber.
                        </p>
                    </div>

                    <span className="rounded-full bg-slate-200 px-3 py-1 text-sm font-medium text-slate-600">
                        {transactions.length} lançamento(s)
                    </span>
                </div>

                <div className="space-y-4">
                    {transactions.length === 0 && (
                        <div className="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center">
                            <p className="font-medium text-slate-700">
                                Nenhum lançamento encontrado.
                            </p>

                            <p className="mt-1 text-sm text-slate-500">
                                Cadastre um novo lançamento ou altere o período filtrado.
                            </p>
                        </div>
                    )}

                    {transactions.map((transaction) => (
                        <div key={transaction.id} className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md" >
                            <div className="flex items-start justify-between gap-4">
                                <div>
                                    <div className="flex items-center gap-3">
                                        <h3 className="text-lg font-semibold text-slate-900">
                                            {transaction.type === 'pay'
                                                ? 'Conta a pagar'
                                                : 'Conta a receber'}
                                        </h3>

                                        <span
                                            className={`rounded-full px-3 py-1 text-xs font-semibold ${
                                                transaction.status === 'open'
                                                    ? 'bg-amber-100 text-amber-700'
                                                    : transaction.status === 'paid_off'
                                                    ? 'bg-emerald-100 text-emerald-700'
                                                    : 'bg-red-100 text-red-700'
                                            }`}
                                        >
                                            {transaction.status === 'open'
                                                ? 'Em aberto'
                                                : transaction.status === 'paid_off'
                                                ? 'Quitado'
                                                : 'Em atraso'}
                                        </span>
                                    </div>

                                    <p className="mt-2 text-sm text-slate-500">
                                        {transaction.contact?.email}
                                    </p>
                                </div>

                                <div className="text-right">
                                    <p
                                        className={`text-2xl font-bold ${
                                            transaction.type === 'receive'
                                                ? 'text-emerald-600'
                                                : 'text-slate-900'
                                        }`}
                                    >
                                        R$ {transaction.amount}
                                    </p>

                                    <p className="mt-1 text-sm text-slate-500">
                                        Vence em {transaction.due_date}
                                    </p>
                                </div>
                            </div>

                            {transaction.status !== 'paid_off' && (
                                <div className="mt-5 border-t border-slate-100 pt-4">
                                    <button
                                        onClick={() =>
                                            router.patch(
                                                `/lancamentos/${transaction.id}/liquidar`
                                            )
                                        }
                                        className="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500 active:scale-95"
                                    >
                                        Quitar lançamento
                                    </button>
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}

export default Index;