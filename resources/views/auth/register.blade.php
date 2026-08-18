<x-guest-layout>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">
            Crie sua conta
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Comece a organizar suas contas a pagar e receber.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Nome -->
        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-slate-700">
                Nome
            </label>

            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Seu nome" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900 transition">

            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- E-mail -->
        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">
                E-mail
            </label>

            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="seuemail@exemplo.com" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900 transition" >

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Senha -->
        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-slate-700" >
                Senha
            </label>

            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Crie uma senha" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900 transition"
            >

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmação -->
        <div>
            <label
                for="password_confirmation"
                class="mb-1 block text-sm font-medium text-slate-700"
            >
                Confirmar senha
            </label>

            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Digite a senha novamente" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900 transition">

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2"
            />
        </div>

        <!-- Criar conta -->
        <button type="submit" class="w-full rounded-xl bg-slate-900 py-3 font-semibold text-white transition duration-200 hover:bg-slate-800 active:scale-[0.98]">
            Criar conta
        </button>

        <!-- Voltar para login -->
        <div class="text-center pt-1">
            <p class="text-sm text-slate-500">
                Já possui uma conta?
            </p>

            <a href="{{ route('login') }}" class="mt-2 inline-flex w-full items-center justify-center rounded-xl border border-slate-300 py-3 font-semibold text-slate-800 transition duration-200 hover:bg-slate-100 active:scale-[0.98]" >
                Entrar
            </a>
        </div>
    </form>

</x-guest-layout>