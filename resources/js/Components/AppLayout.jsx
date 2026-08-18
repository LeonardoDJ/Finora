import { Link, router } from '@inertiajs/react';

export default function AppLayout({ children }) {
    const handleLogout = () => {
        router.post('/logout');
    };

    return (
        <div className="min-h-screen bg-slate-100 text-slate-900">
            <header className="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur">
                <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                    <div className="flex items-center gap-8">
                        <Link
                            href="/dashboard"
                            className="text-2xl font-bold tracking-tight text-slate-900"
                        >
                            Finora
                        </Link>

                        <nav className="flex items-center gap-2">
                            <Link href="/dashboard" className="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950" >
                                Dashboard
                            </Link>

                            <Link href="/lancamentos" className="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">
                                Lançamentos
                            </Link>

                            <Link href="/contatos" className="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950" >
                                Contatos
                            </Link>
                        </nav>
                    </div>

                    <button onClick={handleLogout} className="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-95" >
                        Sair
                    </button>
                </div>
            </header>

            <main className="mx-auto max-w-7xl px-6 py-8">
                {children}
            </main>
        </div>
    );
}