<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts e Estilos Dinâmicos -->
        @if(str_contains(request()->getHost(), 'ngrok-free.dev'))
            <link rel="stylesheet" href="{{ asset('build/assets/app-Da7P1qpd.css') }}">
            <script src="{{ asset('build/assets/app-ByW0VTRm.js') }}" defer></script>
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                --bg-black: #121214;
                --brand-primary: #4f46e5;
                --brand-secondary: #9333ea;
                --off-white: #f0eee9;
            }
            body { background-color: var(--bg-black) !important; color: var(--off-white) !important; }
            .bg-card { background-color: #0a0a0a !important; border: 1px solid rgba(255,255,255,0.05); }
            .text-brand { color: var(--brand-primary) !important; }
            .bg-brand-gradient { background: linear-gradient(to right, var(--brand-primary), var(--brand-secondary)) !important; }
            .nav-dark { background-color: rgba(18, 18, 20, 0.5) !important; backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255,255,255,0.05); }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#000000] text-[#f0eee9]">
        <div class="min-h-screen bg-[#000000]">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-[#050505] shadow-sm border-b border-white/5">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                    @if(session('success'))
                        <div class="bg-indigo-900/20 border border-indigo-500/50 text-indigo-400 px-4 py-3 rounded-2xl relative mb-4 font-bold text-sm shadow-lg shadow-indigo-500/10">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-900/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-2xl relative mb-4 font-bold text-sm shadow-lg shadow-red-500/10">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>