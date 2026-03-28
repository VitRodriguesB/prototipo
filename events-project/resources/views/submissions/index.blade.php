<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Grade Científica') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#121214]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensagens de Sucesso/Erro Estilizadas --}}
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 rounded-2xl flex items-center shadow-lg shadow-emerald-500/5">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold uppercase tracking-tight text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-[#0a0a0a] overflow-hidden shadow-2xl rounded-2xl border border-white/5">
                <div class="p-6 sm:p-10 text-[#f0eee9]">
                    
                    <div class="mb-10 flex items-center justify-between">
                        <div>
                            <h3 class="text-3xl font-black text-white uppercase tracking-tight italic pe-4">Grade <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600">Científica</span></h3>
                            <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-widest">Gerenciamento de trabalhos e cronograma de apresentações.</p>
                        </div>
                        <div class="px-6 py-2 bg-white/5 border border-white/10 rounded-2xl text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em]">
                            {{ $works->count() }} Submissões
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-white/5">
                        <table class="min-w-full divide-y divide-white/5">
                            <thead class="bg-black/20">
                                <tr>
                                    <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Trabalho / Autor</th>
                                    <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Avaliação</th>
                                    <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Agenda</th>
                                    <th class="px-6 py-5 text-center text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse ($works as $work)
                                    <tr class="hover:bg-white/[0.02] transition-colors group">
                                        <td class="px-6 py-6">
                                            <div class="text-sm font-black text-white leading-tight uppercase italic group-hover:text-indigo-400 transition-colors">{{ $work->title }}</div>
                                            <div class="flex items-center gap-2 mt-2">
                                                <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest">{{ $work->user->name }}</span>
                                                <span class="text-[9px] text-slate-600 uppercase font-bold">• {{ $work->workType->type }}</span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-6">
                                            @if($work->reviews->count() > 0)
                                                @foreach($work->reviews as $review)
                                                    <div class="mb-2 p-3 bg-white/5 border border-white/5 rounded-2xl">
                                                        <div class="flex justify-between items-center mb-1">
                                                            <span class="text-[8px] font-black text-slate-500 uppercase">{{ $review->user->name }}</span>
                                                            <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase
                                                                {{ $review->status == 1 ? 'bg-emerald-500/10 text-emerald-400' : ($review->status == 2 ? 'bg-red-500/10 text-red-400' : 'bg-orange-500/10 text-orange-400') }}">
                                                                {{ $review->status == 1 ? 'Aprovado' : ($review->status == 2 ? 'Reprovado' : 'Pendente') }}
                                                            </span>
                                                        </div>
                                                        @if($review->status > 0)
                                                            <div class="text-[10px] text-slate-400 italic mt-1 line-clamp-1">"{{ $review->comments }}"</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <form action="{{ route('submissions.assign', $work) }}" method="POST" class="flex gap-2">
                                                    @csrf
                                                    <select name="user_id" class="text-[9px] font-black uppercase tracking-widest rounded-xl bg-[#121214] border-white/10 text-slate-400 py-2 px-4 focus:ring-indigo-500 focus:border-indigo-500" required>
                                                        <option value="">Atribuir Avaliador...</option>
                                                        @foreach($reviewers as $rev)
                                                            <option value="{{ $rev->id }}">{{ $rev->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-[9px] font-black rounded-xl hover:bg-indigo-700 uppercase tracking-widest transition-all">OK</button>
                                                </form>
                                            @endif
                                        </td>

                                        <td class="px-6 py-6">
                                            <form action="{{ route('submissions.schedule', $work) }}" method="POST" class="space-y-2">
                                                @csrf
                                                <div class="flex flex-col gap-2">
                                                    <input type="datetime-local" name="presentation_date" value="{{ $work->presentation_date ? $work->presentation_date->format('Y-m-d\TH:i') : '' }}" 
                                                           class="text-[9px] font-black uppercase rounded-xl bg-[#121214] border-white/10 text-slate-400 py-2 px-3 w-full" required>
                                                    <input type="text" name="presentation_room" value="{{ $work->presentation_room }}" placeholder="Sala/Link"
                                                           class="text-[9px] font-black uppercase rounded-xl bg-[#121214] border-white/10 text-slate-400 py-2 px-3 w-full" required>
                                                </div>
                                                <div class="flex gap-2">
                                                    <input type="number" name="presentation_order" value="{{ $work->presentation_order }}" placeholder="Ordem"
                                                           class="text-[9px] font-black uppercase rounded-xl bg-[#121214] border-white/10 text-slate-400 py-2 px-3 w-16" required>
                                                    <button type="submit" class="flex-1 bg-white/5 text-white text-[9px] font-black rounded-xl hover:bg-white hover:text-black uppercase tracking-widest transition-all">Agendar</button>
                                                </div>
                                            </form>
                                        </td>

                                        <td class="px-6 py-6 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <a href="{{ route('works.download', $work) }}" class="p-3 bg-white/5 text-slate-400 rounded-2xl hover:bg-indigo-600 hover:text-white transition-all shadow-xl group/btn" title="Baixar Trabalho">
                                                    <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                </a>

                                                @if($work->presentation_date && !$work->inscription->presented_work)
                                                    <form action="{{ route('submissions.confirm', $work) }}" method="POST" onsubmit="return confirm('Confirmar presença do autor?');">
                                                        @csrf
                                                        <button type="submit" class="px-4 py-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl text-[8px] font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all">
                                                            Confirmar
                                                        </button>
                                                    </form>
                                                @elseif($work->inscription->presented_work)
                                                    <span class="px-4 py-2 bg-emerald-500 text-white rounded-xl text-[8px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20">
                                                        Apresentado
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-24 text-center text-slate-600 font-black uppercase tracking-[0.5em] italic text-xs">Nenhum trabalho submetido</td>
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