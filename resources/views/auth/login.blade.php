<x-guest-layout>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">
            Bem-vindo de volta
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Entre na sua conta para acessar o Finora.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                E-mail
            </label>

            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="seuemail@exemplo.com" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900 transition"
            >

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                Senha
            </label>

            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Digite sua senha" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900 transition" >

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900" >

                <span class="ml-2 text-sm text-slate-600">
                    Lembrar de mim
                </span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-slate-700 hover:text-slate-950 transition" >
                    Esqueci minha senha
                </a>
            @endif
        </div>

        <button type="submit" class="w-full rounded-xl bg-slate-900 py-3 text-white font-semibold hover:bg-slate-800 active:scale-[0.98] transition duration-200">
            Entrar
        </button>

        @if (Route::has('register'))
            <div class="text-center pt-2">
                <p class="text-sm text-slate-500">
                    Ainda não possui uma conta?
                </p>

                <a href="{{ route('register') }}" class="mt-2 inline-flex w-full items-center justify-center rounded-xl border border-slate-300 py-3 font-semibold text-slate-800 hover:bg-slate-100 active:scale-[0.98]transition duration-200" >
                    Criar conta
                </a>
            </div>
        @endif
    </form>

</x-guest-layout>