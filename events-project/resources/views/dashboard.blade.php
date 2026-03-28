<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#121214]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Mensagens de Alerta Estilizadas --}}
            @if(session('success'))
                <div class="mb-8 p-4 bg-indigo-500/10 border border-indigo-500/50 text-indigo-400 rounded-2xl flex items-center shadow-lg shadow-indigo-500/5">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold uppercase tracking-tight text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if(Auth::user()->user_type_id == 1)
                <div class="space-y-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                        <div>
                            <h3 class="text-3xl font-black text-white uppercase tracking-tight italic pe-4 pr-3">Meu <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Desempenho</span></h3>
                            <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-2">Visão geral da sua jornada acadêmica</p>
                        </div>
                        <div class="px-6 py-2 bg-white/5 border border-white/10 rounded-2xl text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em]">
                            Atualizado em Tempo Real
                        </div>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-8">
                        <div class="p-8 bg-[#0a0a0a] rounded-2xl border border-white/5 shadow-2xl relative overflow-hidden group">
                            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">Total Inscritos</span>
                            <span class="text-5xl font-black text-white leading-none tracking-tighter">{{ $stats['total_inscriptions'] ?? 0 }}</span>
                        </div>
                        <div class="p-8 bg-[#0a0a0a] rounded-2xl border border-white/5 shadow-2xl relative overflow-hidden group">
                            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">Confirmados</span>
                            <span class="text-5xl font-black text-emerald-400 leading-none tracking-tighter">{{ $stats['confirmed_inscriptions'] ?? 0 }}</span>
                        </div>
                        <div class="p-8 bg-[#0a0a0a] rounded-2xl border border-white/5 shadow-2xl relative overflow-hidden group">
                            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all"></div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">Trabalhos</span>
                            <span class="text-5xl font-black text-purple-400 leading-none tracking-tighter">{{ $stats['submitted_works'] ?? 0 }}</span>
                        </div>
                        <div class="p-8 bg-[#0a0a0a] rounded-2xl shadow-2xl relative overflow-hidden group border border-indigo-500/20">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/10 to-transparent"></div>
                            <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest block mb-2 relative z-10">Presenças</span>
                            <span class="text-5xl font-black text-white leading-none tracking-tighter relative z-10">{{ $stats['confirmed_presences'] ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-black text-white uppercase tracking-widest border-l-4 border-[#4f46e5] pl-4">Minhas Inscrições</h3>
                            <a href="/" class="text-xs font-black text-indigo-400 hover:text-white transition-colors uppercase tracking-widest">Explorar mais eventos</a>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-8">
                            @forelse($userInscriptions ?? [] as $inscription)
                                <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-6 sm:p-10 shadow-2xl relative overflow-hidden group">
                                    
                                    <div class="flex justify-between items-start border-b border-white/5 pb-6 mb-8 relative z-10">
                                        <div>
                                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-2xl text-[10px] font-black uppercase tracking-widest mb-3 inline-block border border-indigo-500/20">
                                                ID: {{ $inscription->registration_code }}
                                            </span>
                                            <h4 class="text-3xl font-black text-white leading-none tracking-tighter uppercase italic group-hover:text-indigo-400 transition-colors">{{ $inscription->event->title }}</h4>
                                            <p class="text-slate-500 mt-2 font-bold text-xs uppercase tracking-widest italic">
                                                {{ \Carbon\Carbon::parse($inscription->event->event_date)->format('d . M . Y') }} • {{ $inscription->event->location }}
                                            </p>
                                        </div>
                                        @if($inscription->status != 1)
                                            <form action="{{ route('inscriptions.destroy', $inscription) }}" method="POST" onsubmit="return confirm('Cancelar inscrição?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-3 bg-white/5 text-slate-500 hover:bg-red-500 hover:text-white rounded-2xl transition-all shadow-xl" title="Cancelar Inscrição">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 relative z-10">
                                        
                                        @if($inscription->status == 1)
                                            <div class="h-24 flex flex-col items-center justify-center p-4 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 text-center">
                                                <svg class="w-6 h-6 text-emerald-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">Inscrição Confirmada</span>
                                            </div>
                                        @else
                                            @if($inscription->payment && $inscription->payment->status == 1)
                                                <div class="h-24 flex flex-col items-center justify-center p-4 rounded-2xl border border-blue-500/30 bg-blue-500/10 text-center animate-pulse">
                                                    <svg class="w-6 h-6 text-blue-400 mb-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
                                                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-400">Validando Pix</span>
                                                </div>
                                            @else
                                                <a href="{{ route('payment.create', $inscription) }}" class="h-24 flex flex-col items-center justify-center p-4 rounded-2xl border border-[#4f46e5]/50 bg-gradient-to-r from-[#4f46e5]/20 to-[#9333ea]/20 hover:from-[#4f46e5] hover:to-[#9333ea] transition-all text-center group/pay">
                                                    <svg class="w-6 h-6 text-white mb-1 group-hover/pay:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                    <span class="text-[10px] font-black uppercase tracking-widest text-white">Pagar via Pix</span>
                                                </a>
                                            @endif
                                        @endif

                                        @if($inscription->status == 1)
                                            @if($inscription->attended)
                                                <div class="h-24 flex flex-col items-center justify-center p-4 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 text-center">
                                                    <svg class="w-6 h-6 text-emerald-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">Presença Confirmada</span>
                                                </div>
                                            @else
                                                <a href="{{ route('ingresso.force.show', $inscription->id) }}" class="h-24 flex flex-col items-center justify-center p-4 rounded-2xl border border-indigo-500/30 bg-indigo-500/10 hover:bg-indigo-500/20 hover:scale-[1.02] transition-all text-center shadow-lg shadow-indigo-500/5 group/qr">
                                                    <svg class="w-6 h-6 text-indigo-400 mb-1 group-hover/qr:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400">Abrir QR Code</span>
                                                </a>
                                            @endif
                                        @else                                            <div class="h-24 flex flex-col items-center justify-center p-4 rounded-2xl border border-white/5 bg-[#121214] text-center opacity-50">
                                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-600">Aguardando Pgto</span>
                                            </div>
                                        @endif

                                        @if($inscription->status == 1)
                                            <a href="{{ route('inscriptions.proof', $inscription) }}" class="h-24 flex flex-col items-center justify-center p-4 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all text-center">
                                                <svg class="w-6 h-6 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">Meu Comprovante</span>
                                            </a>
                                        @else
                                             <div class="h-24 flex flex-col items-center justify-center p-4 rounded-2xl border border-white/5 bg-[#121214] text-center opacity-50">
                                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-600">Comprovante Bloqueado</span>
                                            </div>
                                        @endif

                                        @if($inscription->status == 1 && $inscription->attended)
                                            <a href="{{ route('certificates.participation', $inscription) }}" class="h-24 flex flex-col items-center justify-center p-4 rounded-2xl border border-indigo-500/50 bg-gradient-to-br from-indigo-600 to-purple-700 hover:scale-[1.02] transition-all text-center shadow-lg shadow-indigo-500/20 group/cert">
                                                <svg class="w-6 h-6 text-white mb-1 group-hover/cert:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                                <span class="text-[10px] font-black uppercase tracking-widest text-white">Certificado</span>
                                            </a>
                                        @else
                                            <div class="h-24 flex flex-col items-center justify-center p-4 rounded-2xl border border-white/5 bg-[#121214] text-center opacity-50">
                                                <svg class="w-6 h-6 text-slate-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-600">Requer Presença</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($inscription->status == 1 && $inscription->inscriptionType->allow_work_submission)
                                        <div class="mt-6 pt-6 border-t border-white/5 relative z-10">
                                            @if($inscription->work_id)
                                                <div class="flex flex-col sm:flex-row items-center justify-between p-4 bg-[#121214] border border-white/5 rounded-2xl">
                                                    <div class="flex items-center gap-4 mb-4 sm:mb-0">
                                                        <div class="p-3 bg-indigo-500/10 rounded-xl">
                                                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        </div>
                                                        <div>
                                                            <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Trabalho Submetido</span>
                                                            <h5 class="text-sm font-bold text-white italic truncate max-w-xs sm:max-w-md">"{{ $inscription->work->title }}"</h5>
                                                        </div>
                                                    </div>

                                                    @php $review = $inscription->work->reviews->where('status', '>', 0)->first(); @endphp
                                                    
                                                    <div class="flex items-center gap-3 w-full sm:w-auto">
                                                        @if($review)
                                                            <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $review->status == 1 ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-red-500/10 text-red-400 border-red-500/30' }}">
                                                                {{ $review->status == 1 ? 'Aprovado' : 'Reprovado' }}
                                                            </span>
                                                        @else
                                                            <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border bg-orange-500/10 text-orange-400 border-orange-500/30">Em Avaliação</span>
                                                        @endif
                                                        
                                                        <a href="{{ route('works.download', $inscription->work) }}" class="p-3 bg-white/5 text-white rounded-xl hover:bg-white/10 transition-all border border-white/5" title="Baixar PDF">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ route('works.create', $inscription->event) }}" class="flex items-center justify-center gap-3 w-full py-4 bg-white/5 border border-white/10 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    Submeter Trabalho Acadêmico
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                </div>
                            @empty
                                <div class="text-center py-20 bg-white/5 rounded-2xl border-2 border-dashed border-white/10">
                                    <p class="text-slate-500 font-black uppercase tracking-widest italic">Você ainda não possui inscrições ativas.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            @if(Auth::user()->user_type_id == 2)
                <div class="space-y-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                        <div>
                            <h3 class="text-3xl font-black text-white uppercase tracking-tight italic pe-4 pr-3">Painel de <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-[#4f46e5] to-[#9333ea]">Gestão</span></h3>
                            <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-2">Visão geral do desempenho dos seus eventos</p>
                        </div>
                        <div class="px-6 py-2 bg-white/5 border border-white/10 rounded-2xl text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em]">
                            Atualizado em Tempo Real
                        </div>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-8">
                        <div class="p-8 bg-[#0a0a0a] rounded-2xl border border-white/5 shadow-2xl relative overflow-hidden group">
                            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">Total Inscritos</span>
                            <span class="text-5xl font-black text-white leading-none tracking-tighter">{{ $stats['total_inscriptions'] ?? 0 }}</span>
                        </div>
                        <div class="p-8 bg-[#0a0a0a] rounded-2xl border border-white/5 shadow-2xl relative overflow-hidden group">
                            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">Inscrições Pagas</span>
                            <span class="text-5xl font-black text-emerald-400 leading-none tracking-tighter">{{ $stats['confirmed_payments'] ?? 0 }}</span>
                        </div>
                        <div class="p-8 bg-[#0a0a0a] rounded-2xl border border-white/5 shadow-2xl relative overflow-hidden group">
                            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl group-hover:bg-orange-500/20 transition-all"></div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">Aguardando Pix</span>
                            <span class="text-5xl font-black text-orange-400 leading-none tracking-tighter">{{ $stats['pending_payments'] ?? 0 }}</span>
                        </div>
                        <div class="p-8 bg-[#0a0a0a] rounded-2xl shadow-2xl relative overflow-hidden group border border-indigo-500/20">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/10 to-transparent"></div>
                            <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest block mb-2 relative z-10">Arrecadação Total</span>
                            <span class="text-3xl font-black text-white leading-none tracking-tighter relative z-10">R$ {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('events.index') }}" class="group p-10 bg-[#0a0a0a] border border-white/5 rounded-2xl hover:border-indigo-500/40 transition-all relative overflow-hidden">
                            <div class="flex items-center gap-6 relative z-10">
                                <div class="w-16 h-16 bg-indigo-500/10 text-indigo-400 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-500 shadow-xl">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-2xl text-white uppercase italic">Meus Eventos</h4>
                                    <p class="text-sm text-slate-500 mt-1 font-medium">Configurar datas, locais e modalidades.</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('organization.payments.index') }}" class="group p-10 bg-[#0a0a0a] border border-white/5 rounded-2xl hover:border-emerald-500/40 transition-all relative overflow-hidden">
                            <div class="flex items-center gap-6 relative z-10">
                                <div class="w-16 h-16 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-xl">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-2xl text-white uppercase italic">Validar Pagamentos</h4>
                                    <p class="text-sm text-slate-500 mt-1 font-medium">Análise de comprovantes Pix e aprovações.</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('attendance.scanner') }}" class="group p-10 bg-[#0a0a0a] border border-white/5 rounded-2xl hover:border-white/20 transition-all relative overflow-hidden">
                            <div class="flex items-center gap-6 relative z-10">
                                <div class="w-16 h-16 bg-white/5 text-white rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-white group-hover:text-black transition-all duration-500 shadow-xl">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-2xl text-white uppercase italic">Scanner QR Code</h4>
                                    <p class="text-sm text-slate-400 mt-1 font-medium">Controle de presença mobile presencial.</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('submissions.index') }}" class="group p-10 bg-[#0a0a0a] border border-white/5 rounded-2xl hover:border-purple-500/40 transition-all relative overflow-hidden">
                            <div class="flex items-center gap-6 relative z-10">
                                <div class="w-16 h-16 bg-purple-500/10 text-purple-400 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all duration-500 shadow-xl">
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

            @if(Auth::user()->user_type_id == 3)
                <div class="space-y-10">
                    <div class="flex justify-between items-end">
                        <div>
                            <h3 class="text-3xl font-black text-white uppercase tracking-tight italic pe-4 pr-3">Comitê <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-indigo-600">Científico</span></h3>
                            <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-2">Trabalhos acadêmicos aguardando seu parecer</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @forelse ($pendingReviews ?? [] as $review)
                            <div class="p-8 bg-[#0a0a0a] border border-white/5 rounded-2xl flex flex-col md:flex-row justify-between items-center gap-8 hover:border-blue-500/40 transition-all shadow-2xl">
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
                            <div class="text-center py-24 bg-white/5 rounded-2xl border-2 border-dashed border-white/10">
                                <p class="text-slate-500 font-black uppercase tracking-widest italic text-sm">Você não possui trabalhos pendentes no momento.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>