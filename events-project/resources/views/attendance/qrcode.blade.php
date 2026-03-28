<x-app-layout>
    <div class="min-h-screen bg-[#121214] flex flex-col items-center justify-center p-6 overflow-x-hidden w-full text-[#f0eee9]">
        <div class="text-center mb-10 select-none">
            <h2 class="text-2xl font-black text-white uppercase italic tracking-widest">Meu <span class="text-indigo-500">Ingresso</span></h2>
            <p class="text-[10px] text-slate-500 font-black uppercase tracking-[0.3em] mt-2">Apresente na portaria do evento</p>
        </div>

        <div class="bg-[#0a0a0a] border border-white/5 p-10 rounded-[2.5rem] shadow-2xl shadow-indigo-500/10 flex flex-col items-center w-full max-w-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
            
            {{-- Fundo branco no QR Code é mandatório para contraste do scanner --}}
            <div class="bg-white p-4 rounded-3xl mb-8 shadow-inner">
                <img src="{{ $qrCodeUri }}" alt="QR Code" class="w-64 h-64 bg-white p-4 rounded-2xl mb-8 shadow-inner">
            </div>

            <div class="text-center space-y-2">
                <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest">ID: {{ $inscription->registration_code }}</span>
                <h3 class="text-xl font-black text-white uppercase leading-tight">{{ $inscription->user->name }}</h3>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-tight italic">{{ $inscription->event->title }}</p>
            </div>
        </div>

        <a href="{{ route('dashboard') }}" class="mt-10 px-8 py-4 bg-white/5 text-white border border-white/10 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-white/10 transition-all flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Voltar ao Painel
        </a>
    </div>
</x-app-layout>
