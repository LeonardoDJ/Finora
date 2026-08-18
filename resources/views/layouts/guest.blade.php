<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Finora') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-950 text-slate-900">
    <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800">

        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <a href="/" class="inline-block">
                    <h1 class="text-4xl font-bold tracking-tight text-white">
                        Finora
                    </h1>

                    <p class="mt-2 text-sm text-slate-400">
                        Controle financeiro simples e organizado
                    </p>
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl px-8 py-8 transition duration-300 hover:-translate-y-1 hover:shadow-slate-950/40">
                {{ $slot }}
            </div>
        </div>

    </div>
</body>
</html>