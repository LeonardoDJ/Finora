<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                Dashboard
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Bem-vindo ao Finora. Acesse rapidamente as principais áreas do sistema.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-6">

            <div
                class="grid gap-6 md:grid-cols-2"
                style="display:grid; grid-template-columns:repeat(2,minmax(0,1fr));"
            >
                <a
                    href="/lancamentos"
                    class="group rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-600">
                                Financeiro
                            </p>

                            <h3 class="mt-2 text-2xl font-bold text-slate-900">
                                Lançamentos
                            </h3>

                            <p class="mt-2 max-w-md text-sm leading-6 text-slate-500">
                                Cadastre contas a pagar e receber, acompanhe vencimentos,
                                liquide lançamentos e feche períodos.
                            </p>
                        </div>

                        <div class="rounded-xl bg-emerald-50 px-4 py-3 text-2xl transition group-hover:bg-emerald-100">
                            $
                        </div>
                    </div>

                    <div class="mt-6 text-sm font-semibold text-slate-800">
                        Acessar lançamentos →
                    </div>
                </a>

                <a
                    href="/contatos"
                    class="group rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">
                                Cadastros
                            </p>

                            <h3 class="mt-2 text-2xl font-bold text-slate-900">
                                Contatos
                            </h3>

                            <p class="mt-2 max-w-md text-sm leading-6 text-slate-500">
                                Gerencie seus clientes e fornecedores utilizados nos
                                lançamentos financeiros.
                            </p>
                        </div>

                        <div class="rounded-xl bg-blue-50 px-4 py-3 text-2xl transition group-hover:bg-blue-100">
                            👤
                        </div>
                    </div>

                    <div class="mt-6 text-sm font-semibold text-slate-800">
                        Acessar contatos →
                    </div>
                </a>
            </div>

            <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-900 p-8 text-white shadow-sm">
                <p class="text-sm font-medium text-slate-400">
                    Finora
                </p>

                <h3 class="mt-2 text-2xl font-bold">
                    Controle financeiro sem complicação.
                </h3>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">
                    Utilize os contatos para organizar clientes e fornecedores,
                    acompanhe contas a pagar e receber e receba avisos automáticos
                    de vencimento.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>