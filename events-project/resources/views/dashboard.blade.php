<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white tracking-tighter uppercase italic">
            {{ __('Central de Controle') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12 bg-[#121214]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Mensagens de Alerta Estilizadas --}}
            @if(session('success'))
                <div class="mb-8 p-4 bg-indigo-500/10 border border-indigo-500/50 text-indigo-400 rounded-3xl flex items-center shadow-lg shadow-indigo-500/5">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold uppercase tracking-tight text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- ===== PAINEL DO PARTICIPANTE (ID 1) ===== -->
            @if(Auth::user()->user_type_id == 1)
                <div class="space-y-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-black text-white uppercase tracking-widest border-l-4 border-[#4f46e5] pl-4">Minhas Inscrições</h3>
                        <a href="/" class="text-xs font-black text-indigo-400 hover:text-white transition-colors uppercase tracking-widest">Explorar mais eventos</a>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        @forelse($userInscriptions ?? [] as $inscription)
                            <div class="bg-[#0a0a0a] border border-white/5 rounded-[2.5rem] p-6 sm:p-10 shadow-2xl hover:border-indigo-500/30 transition-all group relative overflow-hidden">
                                <div class="absolute top-0 right-0 p-8 opacity-5">
                                    <x-application-logo class="h-24 w-auto text-white opacity-20" />
                                </div>

                                <div class="relative z-10">
                                    <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                                        <div class="flex-1">
                                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 inline-block border border-indigo-500/20">
                                                {{ $inscription->registration_code }}
                                            </span>
                                            <h4 class="text-3xl font-black text-white leading-none tracking-tighter uppercase italic group-hover:text-indigo-400 transition-colors">{{ $inscription->event->title }}</h4>
                                            <p class="text-slate-500 mt-2 font-bold text-xs uppercase tracking-widest italic">
                                                {{ \Carbon\Carbon::parse($inscription->event->event_date)->format('d . M . Y') }} • {{ $inscription->event->location }}
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-4">
                                            <span class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border
                                                {{ $inscription->status == 1 ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-orange-500/10 text-orange-400 border-orange-500/30' }}">
                                                {{ $inscription->status == 1 ? 'Inscrição Confirmada' : 'Aguardando Aprovação' }}
                                            </span>
                                            
                                            @if($inscription->status != 1)
                                                <form action="{{ route('inscriptions.destroy', $inscription) }}" method="POST" onsubmit="return confirm('Cancelar inscrição?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-3 bg-white/5 text-slate-500 hover:bg-red-500 hover:text-white rounded-2xl transition-all shadow-xl">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                                        {{-- Lado 1: Status Financeiro/Científico --}}
                                        <div class="lg:col-span-2 space-y-6">
                                            {{-- Pagamento --}}
                                            @if($inscription->status != 1)
                                                <div class="p-6 bg-white/5 rounded-3xl border border-white/5">
                                                    @if($inscription->payment && $inscription->payment->status == 1)
                                                        <div class="flex items-center gap-4">
                                                            <div class="w-12 h-12 bg-blue-500/20 text-blue-400 rounded-2xl flex items-center justify-center animate-pulse">
                                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
                                                            </div>
                                                            <div>
                                                                <h5 class="text-sm font-black text-white uppercase italic">Análise de Comprovante</h5>
                                                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">O organizador foi notificado e validará seu Pix em breve.</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <a href="{{ route('payment.create', $inscription) }}" class="flex items-center justify-center gap-3 w-full py-5 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-[1.5rem] font-black text-xs uppercase tracking-widest shadow-2xl shadow-indigo-500/20 hover:scale-[1.02] transition-all">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                            Pagar Inscrição via Pix
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif

                                            {{-- Trabalho --}}
                                            @if($inscription->status == 1)
                                                @if($inscription->work_id)
                                                    <div class="p-6 bg-slate-900 rounded-[2rem] border border-white/5 relative group/work">
                                                        <div class="flex justify-between items-start mb-4">
                                                            <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Trabalho Submetido</span>
                                                            <a href="{{ route('works.download', $inscription->work) }}" class="p-2 bg-white/5 text-white rounded-xl hover:bg-indigo-600 transition-all">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                            </a>
                                                        </div>
                                                        <h5 class="text-lg font-black text-white italic">"{{ $inscription->work->title }}"</h5>
                                                        
                                                        {{-- Parecer --}}
                                                        @php $review = $inscription->work->reviews->where('status', '>', 0)->first(); @endphp
                                                        @if($review)
                                                            <div class="mt-6 p-4 bg-black/40 rounded-2xl border border-white/5">
                                                                <div class="flex justify-between items-center mb-2">
                                                                    <span class="text-[9px] font-black uppercase text-slate-500">Parecer do Comitê</span>
                                                                    <span class="text-[10px] font-black {{ $review->status == 1 ? 'text-emerald-400' : 'text-red-400' }}">
                                                                        {{ $review->status == 1 ? 'TRABALHO APROVADO' : 'TRABALHO REPROVADO' }}
                                                                    </span>
                                                                </div>
                                                                <p class="text-sm text-slate-400 italic">"{{ $review->comments }}"</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($inscription->inscriptionType->allow_work_submission)
                                                    <a href="{{ route('works.create', $inscription->event) }}" class="flex items-center justify-center gap-3 w-full py-5 bg-white text-black rounded-[1.5rem] font-black text-xs uppercase tracking-widest hover:bg-[#f0eee9] transition-all shadow-xl shadow-white/5">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                                        Submeter Trabalho Acadêmico
                                                    </a>
                                                @endif
                                            @endif
                                        </div>

                                        {{-- Lado 2: QR Code e Docs --}}
                                        <div class="flex flex-col gap-4">
                                            @if($inscription->status == 1)
                                                <div class="p-6 bg-white rounded-[2.5rem] flex flex-col items-center justify-center shadow-inner group/qr">
                                                    @if(!$inscription->attended)
                                                        <img src="{{ $inscription->qr_code_url }}" alt="QR Code" class="w-32 h-32 mb-4 group-hover:scale-110 transition-transform duration-500">
                                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Check-in Necessário</span>
                                                    @else
                                                        <div class="w-16 h-16 bg-emerald-500 text-white rounded-full flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/20">
                                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                                        </div>
                                                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Presença Confirmada</span>
                                                    @endif
                                                </div>

                                                <div class="space-y-2">
                                                    <a href="{{ route('inscriptions.proof', $inscription) }}" class="flex items-center justify-center py-3 bg-white/5 text-slate-400 border border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white/10 hover:text-white transition-all">Comprovante de Inscrição</a>
                                                    
                                                    @if($inscription->attended)
                                                        <a href="{{ route('certificates.participation', $inscription) }}" class="flex items-center justify-center py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-500/20">Certificado de Participação</a>
                                                    @endif
                                                    
                                                    @if($inscription->presented_work)
                                                        <a href="{{ route('certificates.presentation', $inscription->work) }}" class="flex items-center justify-center py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl">Certificado Apresentação</a>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="p-10 border-2 border-dashed border-white/5 rounded-[2.5rem] flex items-center justify-center text-center">
                                                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.3em]">Aguardando Confirmação do Organizador</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-20 bg-white/5 rounded-[3rem] border-2 border-dashed border-white/10">
                                <p class="text-slate-500 font-black uppercase tracking-widest italic">Você ainda não possui inscrições ativas.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- ===== PAINEL DO ORGANIZADOR (ID 2) ===== -->
            @if(Auth::user()->user_type_id == 2)
                <div class="space-y-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                        <div>
                            <h3 class="text-3xl font-black text-white uppercase tracking-tighter italic">Painel de <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#4f46e5] to-[#9333ea]">Gestão</span></h3>
                            <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-2">Visão geral do desempenho dos seus eventos</p>
                        </div>
                        <div class="px-6 py-2 bg-white/5 border border-white/10 rounded-full text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em]">
                            Atualizado em Tempo Real
                        </div>
                    </div>

                    <!-- Grid de Estatísticas Vibrante -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-8">
                        <div class="p-8 bg-[#0a0a0a] rounded-[2.5rem] border border-white/5 shadow-2xl relative overflow-hidden group">
                            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">Total Inscritos</span>
                            <span class="text-5xl font-black text-white leading-none tracking-tighter">{{ $stats['total_inscriptions'] ?? 0 }}</span>
                        </div>
                        <div class="p-8 bg-[#0a0a0a] rounded-[2.5rem] border border-white/5 shadow-2xl relative overflow-hidden group">
                            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">Inscrições Pagas</span>
                            <span class="text-5xl font-black text-emerald-400 leading-none tracking-tighter">{{ $stats['confirmed_payments'] ?? 0 }}</span>
                        </div>
                        <div class="p-8 bg-[#0a0a0a] rounded-[2.5rem] border border-white/5 shadow-2xl relative overflow-hidden group">
                            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl group-hover:bg-orange-500/20 transition-all"></div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">Aguardando Pix</span>
                            <span class="text-5xl font-black text-orange-400 leading-none tracking-tighter">{{ $stats['pending_payments'] ?? 0 }}</span>
                        </div>
                        <div class="p-8 bg-slate-900 rounded-[2.5rem] shadow-2xl relative overflow-hidden group border border-indigo-500/20">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/10 to-transparent"></div>
                            <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest block mb-2 relative z-10">Arrecadação Total</span>
                            <span class="text-3xl font-black text-white leading-none tracking-tighter relative z-10">R$ {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Atalhos de Gestão -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('events.index') }}" class="group p-10 bg-[#0a0a0a] border border-white/5 rounded-[3rem] hover:border-indigo-500/40 transition-all relative overflow-hidden">
                            <div class="flex items-center gap-6 relative z-10">
                                <div class="w-16 h-16 bg-indigo-500/10 text-indigo-400 rounded-[1.5rem] flex items-center justify-center group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-500 shadow-xl">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-2xl text-white uppercase italic">Meus Eventos</h4>
                                    <p class="text-sm text-slate-500 mt-1 font-medium">Configurar datas, locais e modalidades.</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('organization.payments.index') }}" class="group p-10 bg-[#0a0a0a] border border-white/5 rounded-[3rem] hover:border-emerald-500/40 transition-all relative overflow-hidden">
                            <div class="flex items-center gap-6 relative z-10">
                                <div class="w-16 h-16 bg-emerald-500/10 text-emerald-400 rounded-[1.5rem] flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-xl">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-2xl text-white uppercase italic">Validar Pagamentos</h4>
                                    <p class="text-sm text-slate-500 mt-1 font-medium">Análise de comprovantes Pix e aprovações.</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('attendance.scanner') }}" class="group p-10 bg-slate-900 border border-white/5 rounded-[3rem] hover:border-white/20 transition-all relative overflow-hidden">
                            <div class="flex items-center gap-6 relative z-10">
                                <div class="w-16 h-16 bg-white/5 text-white rounded-[1.5rem] flex items-center justify-center group-hover:scale-110 group-hover:bg-white group-hover:text-black transition-all duration-500 shadow-xl">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-2xl text-white uppercase italic">Scanner QR Code</h4>
                                    <p class="text-sm text-slate-400 mt-1 font-medium">Controle de presença mobile presencial.</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('submissions.index') }}" class="group p-10 bg-[#0a0a0a] border border-white/5 rounded-[3rem] hover:border-purple-500/40 transition-all relative overflow-hidden">
                            <div class="flex items-center gap-6 relative z-10">
                                <div class="w-16 h-16 bg-purple-500/10 text-purple-400 rounded-[1.5rem] flex items-center justify-center group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all duration-500 shadow-xl">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-2xl text-white uppercase italic">Grade Científica</h4>
                                    <p class="text-sm text-slate-500 mt-1 font-medium">Gerenciar submissões e avaliações.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            <!-- ===== PAINEL DO AVALIADOR (ID 3) ===== -->
            @if(Auth::user()->user_type_id == 3)
                <div class="space-y-10">
                    <div class="flex justify-between items-end">
                        <div>
                            <h3 class="text-3xl font-black text-white uppercase tracking-tighter italic">Comitê <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-indigo-600">Científico</span></h3>
                            <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-2">Trabalhos acadêmicos aguardando seu parecer</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @forelse ($pendingReviews ?? [] as $review)
                            <div class="p-8 bg-[#0a0a0a] border border-white/5 rounded-[3rem] flex flex-col md:flex-row justify-between items-center gap-8 hover:border-blue-500/40 transition-all shadow-2xl">
                                <div class="text-center md:text-left flex-1">
                                    <span class="text-[9px] font-black text-blue-400 uppercase tracking-[0.3em] mb-2 block">Novo Trabalho para Avaliação</span>
                                    <h4 class="font-black text-2xl text-white leading-tight uppercase italic">{{ $review->work->title }}</h4>
                                    <p class="text-sm font-bold text-slate-500 mt-2 uppercase tracking-widest">Autor: {{ $review->work->user->name }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('works.download', $review->work) }}" class="p-4 bg-white/5 text-white rounded-2xl hover:bg-blue-600 transition-all shadow-xl" title="Baixar Arquivo">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>
                                    <a href="{{ route('reviews.edit', $review) }}" class="px-10 py-5 bg-[#f0eee9] text-black font-black rounded-2xl text-center shadow-2xl hover:bg-blue-600 hover:text-white transition-all uppercase tracking-widest text-xs">
                                        Avaliar Agora
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-24 bg-white/5 rounded-[4rem] border-2 border-dashed border-white/10">
                                <p class="text-slate-500 font-black uppercase tracking-widest italic text-sm">Você não possui trabalhos pendentes no momento.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>