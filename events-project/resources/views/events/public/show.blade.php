<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $event->title }} - {{ config('app.name', 'Sistema de Eventos') }}</title>

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
            body { background-color: var(--bg-black); color: var(--off-white); }
            .nav-blur { background-color: rgba(18, 18, 20, 0.5); backdrop-filter: blur(15px); }
            .bg-glass { background-color: rgba(10, 10, 10, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#121214] overflow-x-hidden text-[#f0eee9]">
        
        <!-- HEADER -->
        <nav class="fixed top-0 w-full nav-blur z-50 border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center">
                            <x-application-logo class="w-auto h-8 object-contain" />
                        </a>
                    </div>
                    <div class="flex items-center gap-6">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-[10px] font-black hover:text-[#4f46e5] uppercase tracking-widest transition-colors">Painel</a>
                            @else
                                <a href="{{ route('login') }}" class="text-[10px] font-black hover:text-[#4f46e5] uppercase tracking-widest transition-colors">Entrar</a>
                                <a href="{{ route('register') }}" class="px-6 py-3 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">Criar Conta</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <main class="pt-16">
            <!-- CAPA DO EVENTO COM OVERLAY -->
            <div class="relative w-full h-[50vh] lg:h-[70vh] bg-black overflow-hidden">
                @if($event->cover_image_path)
                    <img src="{{ str_starts_with($event->cover_image_path, 'http') ? $event->cover_image_path : asset('storage/' . $event->cover_image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover opacity-70 transition-all duration-1000">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-[#1a1a1d] to-black flex items-center justify-center">
                        <x-application-logo class="h-24 w-auto text-white/5 opacity-10" />
                    </div>
                @endif
                
                <div class="absolute inset-0 bg-gradient-to-t from-[#121214] via-transparent to-transparent"></div>

                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 text-center">
                        <span class="px-4 py-1 bg-[#4f46e5] text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] mb-6 inline-block shadow-2xl shadow-indigo-500/40">Inscrições Abertas</span>
                        <h1 class="text-5xl lg:text-8xl font-black text-white leading-none tracking-tighter uppercase italic drop-shadow-2xl">
                            {{ $event->title }}
                        </h1>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 pb-32">
                    
                    <!-- CONTEÚDO ESQUERDA -->
                    <div class="lg:col-span-2 space-y-12">
                        <!-- Card: Sobre -->
                        <div class="bg-glass p-8 sm:p-12 rounded-2xl shadow-2xl">
                            <h2 class="text-[10px] font-black text-[#4f46e5] uppercase tracking-[0.5em] mb-6">Sobre o Evento</h2>
                            <div class="text-xl text-slate-400 leading-relaxed italic">
                                {{ $event->description }}
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mt-12 pt-12 border-t border-white/5">
                                <div>
                                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest block mb-2">Data e Hora</span>
                                    <p class="text-lg font-black text-white uppercase italic">{{ \Carbon\Carbon::parse($event->event_date)->format('d . M . Y') }}</p>
                                    <p class="text-sm text-[#4f46e5] font-bold">Às {{ \Carbon\Carbon::parse($event->event_date)->format('H:i') }}h</p>
                                </div>
                                <div>
                                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest block mb-2">Localização</span>
                                    <p class="text-lg font-black text-white uppercase italic">{{ $event->location }}</p>
                                    <p class="text-sm text-slate-500 font-bold uppercase tracking-tighter mt-1">Presencial / Acadêmico</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card: Programação -->
                        <div class="bg-glass p-8 sm:p-12 rounded-2xl shadow-2xl">
                            <h2 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em] mb-10">Cronograma de Atividades</h2>
                            <div class="space-y-6">
                                @forelse($event->activities->sortBy('start_time') as $activity)
                                    <div class="flex gap-6 items-start p-6 bg-white/5 rounded-2xl border border-white/5 group hover:border-indigo-500/30 transition-all">
                                        <div class="text-center">
                                            <span class="block text-xl font-black text-white leading-none">{{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }}</span>
                                            <span class="text-[8px] font-black text-[#4f46e5] uppercase mt-1 block">Início</span>
                                        </div>
                                        <div class="h-12 w-px bg-white/10"></div>
                                        <div>
                                            <h4 class="text-lg font-black text-[#f0eee9] uppercase italic group-hover:text-white transition-colors">{{ $activity->title }}</h4>
                                            <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ $activity->description }}</p>
                                            <span class="mt-2 inline-block px-3 py-1 bg-white/5 rounded-2xl text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $activity->location }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-slate-600 italic font-bold text-center py-10 uppercase tracking-widest">Aguardando definição da agenda.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- SIDEBAR DIREITA (AÇÕES) -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-28 space-y-6">
                            <!-- Card de Inscrição Premium -->
                            <div class="p-10 bg-gradient-to-br from-[#1a1a1d] to-black rounded-2xl shadow-2xl border border-white/10 relative overflow-hidden group">
                                <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-600/10 rounded-full blur-3xl group-hover:bg-indigo-600/20 transition-all"></div>
                                
                                <h3 class="text-2xl font-black text-white mb-8 uppercase tracking-tighter italic">Grade de <span class="text-[#4f46e5]">Valores</span></h3>
                                
                                <div class="space-y-4 mb-10">
                                    @foreach($event->inscriptionTypes as $type)
                                        <div class="flex justify-between items-center p-5 bg-white/5 rounded-2xl border border-white/5 hover:border-indigo-500/20 transition-all">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-white uppercase tracking-widest">{{ $type->type }}</span>
                                                @if($type->allow_work_submission)
                                                    <span class="text-[8px] font-black text-[#4f46e5] uppercase tracking-widest mt-1">+ Envio de Trabalho</span>
                                                @endif
                                            </div>
                                            <span class="text-xl font-black text-[#f0eee9]">R$ {{ number_format($type->price, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                @if($event->registration_deadline >= now())
                                    <a href="{{ route('inscriptions.create', $event) }}" class="block w-full text-center py-6 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl font-black uppercase tracking-[0.2em] text-xs shadow-[0_20px_50px_rgba(79,70,229,0.3)] hover:scale-[1.03] transition-all">
                                        Garantir Inscrição
                                    </a>
                                @else
                                    <button disabled class="w-full py-6 bg-white/5 text-slate-600 rounded-2xl font-black uppercase tracking-widest cursor-not-allowed border border-white/5">
                                        Prazo Encerrado
                                    </button>
                                @endif

                                <p class="mt-8 text-center text-[9px] text-slate-600 font-black uppercase tracking-[0.3em]">
                                    Expira em: {{ \Carbon\Carbon::parse($event->registration_deadline)->format('d/m/Y') }}
                                </p>
                            </div>

                            <!-- Info Adicional -->
                            <div class="p-8 bg-white/5 rounded-2xl border border-white/5">
                                <div class="flex items-center gap-4 mb-4 text-[#4f46e5]">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    <h4 class="font-black text-[10px] uppercase tracking-[0.2em]">Check-in Digital</h4>
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed font-medium">
                                    Após a confirmação, seu QR Code será gerado automaticamente para validação presencial no evento.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <footer class="bg-black py-32 border-t border-white/5 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <x-application-logo class="h-16 w-auto mx-auto mb-12 opacity-20 grayscale brightness-0 invert" />
                <p class="text-slate-700 text-[9px] font-black uppercase tracking-[1em] mb-4">MATHEUS LIMA &bull; 2026</p>
            </div>
        </footer>

    </body>
</html>