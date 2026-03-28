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
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --bg-black: #121214;
                --brand-primary: #4f46e5;
                --brand-secondary: #9333ea;
                --off-white: #f0eee9;
            }
            body { background-color: var(--bg-black); color: var(--off-white); }
            .nav-blur { background-color: rgba(18, 18, 20, 0.8); backdrop-filter: blur(15px); }
            .bg-glass { background-color: rgba(10, 10, 10, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#121214] overflow-x-hidden text-[#f0eee9]">
        
        <!-- HEADER -->
        <nav class="fixed top-0 w-full nav-blur z-[100] border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center max-w-[60%] overflow-hidden">
                        <a href="/" class="flex items-center">
                            {{-- FIX 1: Logo Responsivo --}}
                            <x-application-logo class="w-auto h-6 md:h-8 object-contain" />
                        </a>
                    </div>
                    <div class="flex items-center gap-4 md:gap-6">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-[10px] font-black hover:text-[#4f46e5] uppercase tracking-widest transition-colors">Painel</a>
                            @else
                                <a href="{{ route('login') }}" class="text-[10px] font-black hover:text-[#4f46e5] uppercase tracking-widest transition-colors">Entrar</a>
                                <a href="{{ route('register') }}" class="px-4 py-2 md:px-6 md:py-3 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">Criar Conta</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <main class="pt-16">
            <!-- CAPA DO EVENTO COM OVERLAY -->
            <div class="relative w-full h-[40vh] md:h-[50vh] lg:h-[70vh] bg-black overflow-hidden">
                @if($event->cover_image_path)
                    <img src="{{ str_starts_with($event->cover_image_path, 'http') ? $event->cover_image_path : asset('storage/' . $event->cover_image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover opacity-60 transition-all duration-1000">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-[#1a1a1d] to-black flex items-center justify-center">
                        <x-application-logo class="h-20 w-auto text-white/5 opacity-10" />
                    </div>
                @endif
                
                <div class="absolute inset-0 bg-gradient-to-t from-[#121214] via-transparent to-transparent"></div>

                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 text-center">
                        <span class="px-4 py-1 bg-[#4f46e5] text-white rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-[0.3em] mb-4 md:mb-6 inline-block shadow-2xl shadow-indigo-500/40">Inscrições Abertas</span>
                        <h1 class="text-3xl md:text-5xl lg:text-8xl font-black text-white leading-none tracking-tighter uppercase italic drop-shadow-2xl px-2">
                            {{ $event->title }}
                        </h1>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 md:-mt-20 relative z-10">
                {{-- FIX 2: Layout Mobile-First em Grade --}}
                <div class="flex flex-col lg:grid lg:grid-cols-3 gap-8 md:gap-12 pb-32">
                    
                    <!-- CONTEÚDO ESQUERDA -->
                    <div class="lg:col-span-2 space-y-8 md:space-y-12">
                        <!-- Card: Sobre -->
                        <div class="bg-glass p-6 md:p-12 rounded-2xl shadow-2xl">
                            <h2 class="text-lg md:text-xl font-black text-white uppercase italic border-l-4 border-indigo-500 pl-3 mb-6">Sobre o Evento</h2>
                            <div class="text-base md:text-xl text-slate-400 leading-relaxed italic">
                                {{ $event->description }}
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:gap-8 mt-8 md:mt-12 pt-8 md:pt-12 border-t border-white/5">
                                <div>
                                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest block mb-2">Data e Hora</span>
                                    <p class="text-base md:text-lg font-black text-white uppercase italic">{{ \Carbon\Carbon::parse($event->event_date)->format('d . M . Y') }}</p>
                                    <p class="text-sm text-[#4f46e5] font-bold">Às {{ \Carbon\Carbon::parse($event->event_date)->format('H:i') }}h</p>
                                </div>
                                <div>
                                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest block mb-2">Localização</span>
                                    <p class="text-base md:text-lg font-black text-white uppercase italic">{{ $event->location }}</p>
                                    <p class="text-[10px] md:text-sm text-slate-500 font-bold uppercase tracking-tighter mt-1">Presencial / Acadêmico</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card: Programação -->
                        <div class="bg-glass p-6 md:p-12 rounded-2xl shadow-2xl">
                            <h2 class="text-lg md:text-xl font-black text-white uppercase italic border-l-4 border-indigo-500 pl-3 mb-10">Cronograma</h2>
                            <div class="space-y-4 md:space-y-6">
                                @forelse($event->activities->sortBy('start_time') as $activity)
                                    <div class="flex flex-col sm:flex-row gap-4 md:gap-6 items-start p-5 md:p-6 bg-white/5 rounded-2xl border border-white/5 group hover:border-indigo-500/30 transition-all">
                                        <div class="flex sm:flex-col items-center sm:text-center gap-3 sm:gap-0">
                                            <span class="block text-xl font-black text-white leading-none">{{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }}</span>
                                            <span class="text-[8px] font-black text-[#4f46e5] uppercase sm:mt-1 block">Início</span>
                                        </div>
                                        <div class="hidden sm:block h-12 w-px bg-white/10"></div>
                                        <div class="w-full">
                                            <h4 class="text-base md:text-lg font-black text-[#f0eee9] uppercase italic group-hover:text-white transition-colors">{{ $activity->title }}</h4>
                                            <p class="text-xs md:text-sm text-slate-500 mt-1 leading-relaxed">{{ $activity->description }}</p>
                                            <span class="mt-3 inline-block px-3 py-1 bg-white/5 rounded-2xl text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $activity->location }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-slate-600 italic font-bold text-center py-10 uppercase tracking-widest text-xs">Aguardando definição da agenda.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- SIDEBAR DIREITA (AÇÕES) -->
                    <div class="lg:col-span-1">
                        <div class="lg:sticky lg:top-28 space-y-6">
                            <!-- Card de Inscrição Premium -->
                            <div class="p-8 md:p-10 bg-[#0a0a0a] rounded-2xl shadow-2xl border border-white/10 relative overflow-hidden group">
                                <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-600/10 rounded-full blur-3xl group-hover:bg-indigo-600/20 transition-all"></div>
                                
                                <h3 class="text-xl md:text-2xl font-black text-white mb-8 uppercase tracking-tighter italic border-l-4 border-indigo-500 pl-3">Grade de <span class="text-[#4f46e5]">Valores</span></h3>
                                
                                <div class="space-y-4 mb-10">
                                    @foreach($event->inscriptionTypes as $type)
                                        <div class="flex justify-between items-center p-4 md:p-5 bg-white/5 rounded-2xl border border-white/5 hover:border-indigo-500/20 transition-all">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] md:text-xs font-black text-white uppercase tracking-widest">{{ $type->type }}</span>
                                                @if($type->allow_work_submission)
                                                    <span class="text-[8px] font-black text-[#4f46e5] uppercase tracking-widest mt-1">+ Envio de Trabalho</span>
                                                @endif
                                            </div>
                                            <span class="text-lg md:text-xl font-black text-[#f0eee9]">R$ {{ number_format($type->price, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <a href="{{ route('inscriptions.create', $event) }}" class="flex items-center justify-center w-full py-5 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl font-black text-xs md:text-sm uppercase tracking-[0.2em] shadow-2xl shadow-indigo-500/40 hover:scale-[1.02] active:scale-95 transition-all">
                                    Garantir minha vaga
                                </a>
                            </div>

                            <!-- Card Auxiliar: Contato -->
                            <div class="p-6 bg-glass rounded-2xl border border-white/5">
                                <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest block mb-4 text-center">Organizado por</span>
                                <div class="flex items-center justify-center gap-4">
                                    <div class="w-10 h-10 bg-indigo-500/10 rounded-full flex items-center justify-center text-indigo-400 font-black text-sm">
                                        {{ substr($event->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-white uppercase tracking-widest">{{ $event->user->name }}</p>
                                        <p class="text-[9px] text-slate-500 font-bold uppercase tracking-tighter">{{ $event->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-black/40 border-t border-white/5 py-12">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <x-application-logo class="h-6 w-auto mx-auto opacity-30 grayscale brightness-0 invert mb-4" />
                <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.5em]">PÁTIO &bull; Sistema de Gestão 2026</p>
            </div>
        </footer>
    </body>
</html>
