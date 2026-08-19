import { Link } from '@inertiajs/react';
import AppLayout from '../Components/AppLayout';

export default function Dashboard() {
    return (
        <AppLayout>
            <div className="mb-8">
                <h1 className="text-3xl font-bold tracking-tight text-slate-900">
                    Dashboard
                </h1>

                <p className="mt-1 text-slate-500">
                    Bem-vindo ao Finora. Acesse rapidamente as principais áreas do sistema.
                </p>
            </div>

            <div
                style={{
                    display: 'grid',
                    gridTemplateColumns: 'repeat(2, minmax(0, 1fr))',
                    gap: '24px',
                }}
            >
                <Link
                    href="/lancamentos"
                    className="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg"
                >
                    <p className="text-sm font-semibold uppercase tracking-wide text-emerald-600">
                        Financeiro
                    </p>

                    <h2 className="mt-2 text-2xl font-bold text-slate-900">
                        Lançamentos
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Cadastre contas a pagar e receber, acompanhe vencimentos,
                        liquide lançamentos e feche períodos.
                    </p>

                    <p className="mt-6 text-sm font-semibold text-slate-800">
                        Acessar lançamentos →
                    </p>
                </Link>

                <Link
                    href="/contatos"
                    className="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg"
                >
                    <p className="text-sm font-semibold uppercase tracking-wide text-blue-600">
                        Cadastros
                    </p>

                    <h2 className="mt-2 text-2xl font-bold text-slate-900">
                        Contatos
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Gerencie seus clientes e fornecedores utilizados nos lançamentos financeiros.
                    </p>

                    <p className="mt-6 text-sm font-semibold text-slate-800">
                        Acessar contatos →
                    </p>
                </Link>
            </div>

            <div className="mt-8 rounded-2xl bg-slate-900 p-8 text-white">
                <p className="text-sm text-slate-400">
                    Finora
                </p>

                <h2 className="mt-2 text-2xl font-bold">
                    Controle financeiro sem complicação.
                </h2>

                <p className="mt-2 text-sm leading-6 text-slate-300">
                    Organize seus contatos, acompanhe contas a pagar e receber
                    e receba avisos automáticos de vencimento.
                </p>
            </div>
        </AppLayout>
    );
}