<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PÁTIO.') }}</title>

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
                --bg-black: #000000;
                --brand-primary: #4f46e5;
                --brand-secondary: #9333ea;
                --off-white: #f0eee9;
            }
            
            body {
                background-color: var(--bg-black) !important;
                margin: 0;
                padding: 0;
            }

            .bg-custom-image {
                position: fixed;
                inset: 0;
                background-image: url('{{ asset('images/background_pro.jpg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                z-index: -2;
            }

            .bg-overlay {
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.85);
                z-index: -1;
            }
            
            @keyframes pulse-light {
                0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
                50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.6; }
            }

            .floating-light {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 1000px;
                height: 1000px;
                background: radial-gradient(circle, rgba(79, 70, 229, 0.2) 0%, rgba(147, 51, 234, 0.1) 40%, transparent 70%);
                filter: blur(150px);
                z-index: -1;
                pointer-events: none;
                animation: pulse-light 8s ease-in-out infinite;
            }

            .brand-card-dark {
                background: linear-gradient(145deg, #0f0f12 0%, #050505 100%) !important;
                box-shadow: 0 60px 120px -20px rgba(0, 0, 0, 1), 0 0 50px rgba(79, 70, 229, 0.15);
                border: 1px solid rgba(255, 255, 255, 0.08);
                position: relative;
                z-index: 10;
                border-radius: 1.5rem !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased overflow-x-hidden text-[#f0eee9]">
        <div class="bg-custom-image"></div>
        <div class="bg-overlay"></div>
        <div class="floating-light"></div>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 pb-12 relative z-10 px-4">
            <div class="w-full sm:max-w-md brand-card-dark">
                <div class="px-8 py-10">
                    {{ $slot }}
                </div>
            </div>
                </div>
            </div>
            <div class="mt-10 text-white/20 text-[10px] font-black uppercase tracking-[0.5em]">
                &copy; {{ date('Y') }} - <span class="text-white/40">{{ config('app.name') }}</span>
            </div>
        </div>
    </body>
</html>