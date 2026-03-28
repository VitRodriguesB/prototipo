<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PATIO.') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-[#121214] relative min-h-screen overflow-y-auto selection:bg-indigo-500 selection:text-white">
        
        <div class="fixed top-[-15%] left-[-10%] w-[50%] h-[50%] bg-indigo-600/20 rounded-full blur-[120px] pointer-events-none z-0"></div>
        <div class="fixed bottom-[-15%] right-[-10%] w-[50%] h-[50%] bg-purple-600/20 rounded-full blur-[120px] pointer-events-none z-0"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 pb-12 sm:pt-0 relative z-10">
            <div class="no-print mb-6">
                <a href="/" class="transition-transform hover:scale-105 inline-block">
                    <div class="no-print mb-6">
                        <a href="/" class="transition-transform hover:scale-105 inline-block">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo PÁTIO" class="h-16 w-auto drop-shadow-2xl">
                        </a>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-10 py-12 bg-[#0a0a0a]/70 backdrop-blur-2xl border border-white/5 shadow-2xl overflow-hidden sm:rounded-[2.5rem] relative">
                
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>