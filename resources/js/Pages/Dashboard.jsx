import AppLayout from '../Components/AppLayout';

export default function Dashboard({
    totalReceber,
    totalPagar,
    saldoPrevisto,
    emAtraso,
}) {
    const formatCurrency = (value) =>
        new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        }).format(value || 0);

    return (
        <AppLayout>
            <div className="mb-8">
                <h1 className="text-3xl font-bold tracking-tight text-slate-900">
                    Dashboard
                </h1>

                <p className="mt-1 text-slate-500">
                    Visão geral da sua situação financeira.
                </p>
            </div>

            <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p className="text-sm font-medium text-slate-500">
                        A receber
                    </p>

                    <p className="mt-2 text-2xl font-bold text-emerald-600">
                        {formatCurrency(totalReceber)}
                    </p>
                </div>

                <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p className="text-sm font-medium text-slate-500">
                        A pagar
                    </p>

                    <p className="mt-2 text-2xl font-bold text-red-600">
                        {formatCurrency(totalPagar)}
                    </p>
                </div>

                <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p className="text-sm font-medium text-slate-500">
                        Saldo previsto
                    </p>

                    <p className="mt-2 text-2xl font-bold text-slate-900">
                        {formatCurrency(saldoPrevisto)}
                    </p>
                </div>

                <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p className="text-sm font-medium text-slate-500">
                        Em atraso
                    </p>

                    <p className="mt-2 text-2xl font-bold text-amber-600">
                        {emAtraso} lançamento(s)
                    </p>
                </div>
            </div>

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                <a href="/lancamentos" className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                >
                    <p className="text-sm font-semibold text-emerald-600">
                        FINANCEIRO
                    </p>

                    <h2 className="mt-3 text-2xl font-bold text-slate-900">
                        Lançamentos
                    </h2>

                    <p className="mt-3 text-slate-500">
                        Cadastre contas a pagar e receber, acompanhe vencimentos e liquidações.
                    </p>
                </a>

                <a href="/contatos" className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                >
                    <p className="text-sm font-semibold text-blue-600">
                        CADASTROS
                    </p>

                    <h2 className="mt-3 text-2xl font-bold text-slate-900">
                        Contatos
                    </h2>

                    <p className="mt-3 text-slate-500">
                        Gerencie seus clientes e fornecedores.
                    </p>
                </a>
            </div>
        </AppLayout>
    );
}