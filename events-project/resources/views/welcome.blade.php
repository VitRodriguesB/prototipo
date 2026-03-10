<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Sistema de Eventos') }}</title>

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
            
            @keyframes float {
                0% { transform: translateY(0px) scale(1.05); }
                50% { transform: translateY(-12px) scale(1.07); }
                100% { transform: translateY(0px) scale(1.05); }
            }
            .floating-active {
                animation: float 4s ease-in-out infinite;
            }

            .perspective-container { perspective: 2000px; }
            .bg-transition { transition: background-image 1.2s ease-in-out, opacity 1.2s ease-in-out; }
            .no-select { -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; -webkit-user-drag: none; }
            .grab-cursor { cursor: grab; }
            .grab-cursor:active { cursor: grabbing; }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#121214] overflow-x-hidden text-[#f0eee9]" 
          x-data="{ 
            active: 3,
            startX: 0,
            isDragging: false,
            events: [
                @foreach($events as $event)
                { 
                    id: {{ $event->id }}, 
                    title: '{{ addslashes($event->title) }}', 
                    date: '{{ \Carbon\Carbon::parse($event->event_date)->format('d/m') }}', 
                    image: '{{ str_starts_with($event->cover_image_path, 'http') ? $event->cover_image_path : asset('storage/' . $event->cover_image_path) }}', 
                    desc: '{{ addslashes(Str::limit($event->description, 120)) }}',
                    url: '{{ route('events.public.show', $event) }}'
                },
                @endforeach
                @if($events->count() == 0)
                { id: 0, title: 'Nenhum evento ativo', date: '--/--', image: '', desc: 'Cadastre eventos no painel.', url: '#' }
                @endif
            ],
            next() { if(this.events.length > 1) this.active = (this.active + 1) % this.events.length },
            prev() { if(this.events.length > 1) this.active = (this.active - 1 + this.events.length) % this.events.length },
            handleStart(e) { this.isDragging = true; this.startX = e.pageX || (e.touches ? e.touches[0].pageX : 0); },
            handleMove(e) {
                if(!this.isDragging) return;
                let currentX = e.pageX || (e.touches ? e.touches[0].pageX : 0);
                let diff = this.startX - currentX;
                if (Math.abs(diff) > 80) { if (diff > 0) this.next(); else this.prev(); this.isDragging = false; }
            },
            getStyle(index) {
                const diff = index - this.active;
                const absDiff = Math.abs(diff);
                
                // Distribuição centralizada: o 'active' fica no 0, 
                // índices menores ficam à esquerda (negativo), maiores à direita (positivo)
                let translateX = diff * 450; 
                let translateZ = absDiff * -400;
                let rotateY = diff * -35;    
                let zIndex = 100 - absDiff;  
                let opacity = 1 - (absDiff * 0.3); 
                let scale = 1 - (absDiff * 0.15);
                
                // Limita a exibição para os vizinhos imediatos para manter a performance e foco
                if (absDiff > 2) opacity = 0; 
                
                return `transform: translateX(${translateX}px) translateZ(${translateZ}px) rotateY(${rotateY}deg) scale(${scale}); 
                        z-index: ${zIndex}; opacity: ${opacity}; transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);`;
            }
          }"
          @pointerdown="handleStart($event)"
          @pointermove="handleMove($event)"
          @pointerup="isDragging = false"
          @keydown.window.left="prev()"
          @keydown.window.right="next()">
        
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <template x-for="(event, index) in events" :key="index">
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-transition grayscale blur-2xl scale-110"
                     :style="'background-image: url(' + event.image + ')'"
                     :class="active === index ? 'opacity-40' : 'opacity-0'">
                </div>
            </template>
            <!-- Overlay de Sombreamento Profundo -->
            <div class="absolute inset-0 bg-gradient-to-b from-[#121214] via-transparent to-[#121214]"></div>
            <div class="absolute inset-0 bg-[#121214]/70"></div>
        </div>

        <nav class="fixed top-0 w-full nav-blur z-[200] border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <x-application-logo class="w-auto h-8 object-contain" />
                    </div>
                    <div class="flex items-center gap-6">
                        @auth
                            <div class="flex items-center gap-4">
                                <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">Painel</a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-black text-white/40 hover:text-red-500 uppercase tracking-widest transition-colors">Sair</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-[10px] font-black hover:text-[#4f46e5] uppercase tracking-widest transition-colors">Entrar</a>
                            <a href="{{ route('register') }}" class="px-6 py-3 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">Criar Conta</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <section class="relative min-h-screen flex flex-col items-center z-10 overflow-hidden grab-cursor no-select" style="padding-top: 100px !important;">
            <div class="text-center mb-10 select-none relative z-20">
                <h2 class="text-[10px] font-black text-[#4f46e5] uppercase tracking-[1.5em] mb-4">Destaques</h2>
                <h1 class="text-4xl lg:text-7xl font-black tracking-tighter uppercase italic text-white" x-text="events[active].title"></h1>
            </div>
            <div class="relative w-full max-w-[100vw] h-[450px] perspective-container flex items-center justify-center">
                <!-- Luz de Fundo (Glow de Profundidade) -->
                <div class="absolute w-[800px] h-[400px] bg-gradient-to-r from-[#4f46e5]/20 to-[#9333ea]/20 rounded-full blur-[120px] animate-pulse pointer-events-none"></div>

                <template x-for="(event, index) in events" :key="index">
                    <div class="absolute transition-all duration-1000 select-none no-select" :style="getStyle(index)">
                        <a :href="event.url" draggable="false" class="block w-[320px] sm:w-[640px] h-[180px] sm:h-[360px] bg-black relative shadow-[0_50px_100px_rgba(0,0,0,0.9)] rounded-2xl overflow-hidden group no-select" :class="active === index ? 'floating-active border border-white/10 shadow-[0_0_100px_rgba(79,70,229,0.4)]' : 'cursor-pointer'">
                            <img :src="event.image" draggable="false" class="w-full h-full object-cover pointer-events-none rounded-2xl transition-all duration-700 no-select" :class="active !== index ? 'grayscale-[0.5] opacity-60' : 'opacity-100'">
                            <div x-show="active !== index" class="absolute inset-0 bg-black/20 transition-opacity duration-1000"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/10 to-transparent p-12 flex flex-col justify-end" x-show="active === index" x-transition.opacity.duration.800ms>
                                <div class="flex items-center gap-4 mb-2">
                                    <span class="px-3 py-1 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white font-black text-[10px] uppercase tracking-widest shadow-xl rounded-2xl" x-text="event.date"></span>
                                    <span class="text-white font-black text-[10px] uppercase tracking-[0.3em] opacity-50">Exclusivo</span>
                                </div>
                                <h3 class="text-3xl font-black text-white leading-tight uppercase italic" x-text="event.title"></h3>
                            </div>
                        </a>
                    </div>
                </template>
            </div>
            <div class="max-w-2xl text-center px-6 mt-20 select-none">
                <p class="text-slate-400 text-lg font-medium leading-relaxed mb-10" x-text="events[active].desc"></p>
                <div class="flex justify-center gap-6">
                    <a :href="events[active].url" class="px-16 py-5 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white font-black rounded-2xl uppercase tracking-widest hover:scale-105 transition-all shadow-[0_20px_50px_rgba(79,72,229,0.3)]">
                        Inscrever-se Agora
                    </a>
                </div>
            </div>
            <div class="flex gap-4 mt-16 pb-10">
                <template x-for="(event, index) in events" :key="index">
                    <button @click="active = index" class="h-1.5 transition-all duration-700 rounded-full" :class="active === index ? 'w-16 bg-[#4f46e5]' : 'w-6 bg-white/10'"></button>
                </template>
            </div>
        </section>

        <section id="eventos" class="py-24 z-10 relative border-t border-white/5 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/background_pro.jpg') }}');">
            <div class="absolute inset-0 bg-black/80 z-0"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="mb-16 flex items-center gap-10">
                    <h2 class="text-4xl font-black uppercase tracking-tighter italic text-white leading-tight">Grade <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-[#4f46e5] to-[#9333ea]">Completa</span></h2>
                    <div class="h-[1px] flex-1 bg-gradient-to-r from-indigo-500/30 to-transparent"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse ($events as $event)
                        <a href="{{ route('events.public.show', $event) }}" class="group bg-[#16161a] border border-white/5 rounded-2xl overflow-hidden hover:bg-[#1c1c21] transition-all duration-500 flex flex-col sm:flex-row h-full sm:min-h-[220px] block">
                            <div class="w-full sm:w-1/3 aspect-video sm:aspect-auto overflow-hidden relative">
                                <img src="{{ str_starts_with($event->cover_image_path, 'http') ? $event->cover_image_path : asset('storage/' . $event->cover_image_path) }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-all duration-1000">
                            </div>
                            <div class="p-8 flex-1 flex flex-col justify-between">
                                <div>
                                    <span class="text-[9px] font-black text-[#4f46e5] uppercase tracking-[0.3em] mb-2 block">{{ \Carbon\Carbon::parse($event->event_date)->format('d . M . Y') }}</span>
                                    <h3 class="text-xl font-black text-[#f0eee9] uppercase italic group-hover:text-white line-clamp-2 leading-tight h-[3rem] overflow-hidden">{{ $event->title }}</h3>
                                </div>
                                <div class="mt-6 flex items-center justify-between border-t border-white/5 pt-4">
                                    <span class="text-lg font-black uppercase text-white">R$ {{ number_format($event->registration_fee, 0, ',', '.') }}</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-[#4f46e5]/40 group-hover:text-[#4f46e5] transition-colors">Detalhes &rarr;</span>
                                </div>
                            </div>                        </a>
                    @empty
                        <p class="col-span-full text-center py-24 text-slate-600 font-black uppercase tracking-widest italic">Nenhum evento disponível.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <footer class="bg-black py-32 border-t border-white/5 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-slate-800">
                <x-application-logo class="w-auto h-16 mx-auto mb-12 opacity-20 grayscale brightness-0 invert" />
                <p class="text-[9px] font-black uppercase tracking-[1em] mb-4">MATHEUS LIMA &bull; 2026</p>
            </div>
        </footer>
    </body>
</html>