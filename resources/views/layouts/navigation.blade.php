<nav class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">

        <div class="flex items-center gap-8">
            <a href="{{ route('dashboard') }}" class="text-2xl font-bold tracking-tight text-slate-900" >
                Finora
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-950' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}" >
                    Dashboard
                </a>

                <a href="/lancamentos" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950" >
                    Lançamentos
                </a>

                <a href="/contatos" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950" >
                    Contatos
                </a>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-sm font-medium text-slate-800">
                    {{ Auth::user()->name }}
                </p>

                <p class="text-xs text-slate-500">
                    {{ Auth::user()->email }}
                </p>
            </div>

            <a href="{{ route('profile.edit') }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100" >
                Perfil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700 active:scale-95" >
                    Sair
                </button>
            </form>
        </div>

    </div>
</nav>