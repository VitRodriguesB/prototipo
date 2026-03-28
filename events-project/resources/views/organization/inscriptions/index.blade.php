<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#121214]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                <div>
                    <h3 class="text-3xl font-black text-white uppercase tracking-tight italic pe-4 pr-3">
                        GESTÃO DE <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">INSCRITOS</span>
                    </h3>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-2">Evento: {{ $event->title }}</p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('events.export', $event) }}" class="px-6 py-3 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all">
                        Exportar CSV
                    </a>
                    <a href="{{ route('attendance.scanner') }}" class="px-6 py-3 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition-all">
                        Abrir Scanner
                    </a>
                </div>
            </div>

            <div class="bg-[#0a0a0a] border border-white/5 shadow-2xl rounded-2xl overflow-hidden">
                <div class="p-6 sm:p-10">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/5">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Participante</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Modalidade</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Pagamento</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-slate-500 uppercase tracking-widest">Presença</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-slate-500 uppercase tracking-widest">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse ($inscriptions as $ins)
                                    <tr class="hover:bg-white/[0.02] transition-colors group">
                                        <td class="px-6 py-6">
                                            <div class="text-sm font-black text-white uppercase italic">{{ $ins->user->name }}</div>
                                            <div class="text-[10px] text-slate-500 mt-1 uppercase tracking-tighter">{{ $ins->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-6 text-xs text-slate-400 font-bold uppercase tracking-widest">
                                            {{ $ins->inscriptionType->type }}
                                        </td>
                                        <td class="px-6 py-6">
                                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border
                                                {{ $ins->status == 1 ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-orange-500/10 text-orange-400 border-orange-500/20' }}">
                                                {{ $ins->status == 1 ? 'Confirmado' : 'Pendente' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-6 text-center">
                                            @if($ins->attended)
                                                <div class="mt-1">
                                                    <form action="{{ route('admin.inscriptions.reset', $ins) }}" method="POST" onsubmit="return confirm('Deseja liberar este QR Code para novo escaneamento?')">
                                                        @csrf
                                                        <button type="submit" class="w-full px-2 py-1 bg-amber-600 text-white text-[9px] font-black uppercase rounded-lg hover:bg-amber-700 transition-all shadow-lg shadow-amber-900/20">
                                                            🔄 Resetar Check-in
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest opacity-50 italic italic">Ausente</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-6 text-center">
                                            {{-- Outras ações se necessário --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-20 text-center text-slate-600 font-black uppercase tracking-[0.3em] italic">Nenhum participante inscrito neste evento</td>
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
