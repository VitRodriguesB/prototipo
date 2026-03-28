<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white tracking-tighter uppercase italic">
            {{ __('Validação de Pagamentos Pix') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#121214]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-[#0a0a0a] border border-white/5 shadow-2xl rounded-[2.5rem] overflow-hidden">
                <div class="p-6 sm:p-10">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/5">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Participante</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Evento / Valor</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-slate-500 uppercase tracking-widest">Comprovante</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-slate-500 uppercase tracking-widest">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse ($pendingPayments as $payment)
                                    <tr class="hover:bg-white/5 transition-colors group">
                                        <td class="px-6 py-6">
                                            <div class="text-sm font-black text-white uppercase italic">{{ $payment->inscription->user->name }}</div>
                                            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">{{ $payment->inscription->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-6">
                                            <div class="text-xs font-bold text-slate-300">{{ $payment->inscription->event->title }}</div>
                                            <div class="text-[10px] font-black text-emerald-400 mt-1 uppercase">R$ {{ number_format($payment->amount, 2, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-6 text-center">
                                            <a href="{{ route('organization.payments.download', $payment) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black text-indigo-400 uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all shadow-xl">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                Ver Arquivo
                                            </a>
                                        </td>
                                        <td class="px-6 py-6">
                                            <div class="flex items-center justify-center gap-3">
                                                <form action="{{ route('organization.payments.approve', $payment->inscription) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-900/20">
                                                        Aprovar
                                                    </button>
                                                </form>

                                                @if($payment->inscription->attended)
                                                    <div class="mt-2">
                                                        <form action="{{ route('admin.inscriptions.reset', $payment->inscription) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="w-full py-2 bg-amber-500/20 border border-amber-500/50 text-amber-500 rounded-xl text-[9px] font-black uppercase tracking-tighter hover:bg-amber-500 hover:text-white transition-all">
                                                                ⚡ Resetar Check-in
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif

                                                <button onclick="document.getElementById('reject-form-{{ $payment->id }}').classList.toggle('hidden')" class="px-6 py-2 bg-white/5 text-red-500 border border-red-500/20 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                                                    Recusar
                                                </button>
                                            </div>

                                            {{-- Formulário de Recusa Expansível --}}
                                            <div id="reject-form-{{ $payment->id }}" class="hidden mt-4 p-4 bg-black/40 rounded-2xl border border-red-500/20 animate-in fade-in slide-in-from-top-2">
                                                <form action="{{ route('organization.payments.reject', $payment->inscription) }}" method="POST">
                                                    @csrf
                                                    <textarea name="rejection_reason" rows="2" class="w-full bg-black border-white/5 rounded-xl text-xs text-white placeholder-slate-600 focus:border-red-500 focus:ring-red-500" placeholder="Motivo da recusa..." required></textarea>
                                                    <button type="submit" class="mt-2 w-full py-2 bg-red-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest">Confirmar Recusa</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-20 text-center text-slate-600 font-black uppercase tracking-[0.3em] italic">Não há pagamentos pendentes para validação</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>