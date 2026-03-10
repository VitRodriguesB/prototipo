<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Gerenciar Trabalhos Submetidos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensagens de Sucesso/Erro --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-2xl font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl rounded-[2rem] border border-slate-100">
                <div class="p-6 sm:p-10 text-slate-900">
                    
                    <h3 class="text-xl font-black text-slate-800 mb-8 uppercase tracking-widest border-b-4 border-indigo-500 inline-block">Trabalhos Recebidos</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Trabalho / Autor</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Avaliação Científica</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Agenda de Apresentação (RF_F11)</th>
                                    <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @forelse ($works as $work)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        {{-- Trabalho / Autor --}}
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-black text-slate-900 leading-tight">{{ $work->title }}</div>
                                            <div class="text-[10px] font-bold text-indigo-600 mt-1 uppercase tracking-tighter">{{ $work->user->name }}</div>
                                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $work->workType->type }}</div>
                                        </td>

                                        {{-- Avaliação --}}
                                        <td class="px-6 py-4">
                                            @if($work->reviews->count() > 0)
                                                @foreach($work->reviews as $review)
                                                    <div class="mb-2 p-2 bg-white border border-slate-100 rounded-xl shadow-sm">
                                                        <div class="flex justify-between items-center mb-1">
                                                            <span class="text-[9px] font-black text-slate-400 uppercase">Rev: {{ $review->user->name }}</span>
                                                            <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase
                                                                {{ $review->status == 1 ? 'bg-green-100 text-green-700' : ($review->status == 2 ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700') }}">
                                                                {{ $review->status == 1 ? 'Aprovado' : ($review->status == 2 ? 'Reprovado' : 'Pendente') }}
                                                            </span>
                                                        </div>
                                                        @if($review->status > 0)
                                                            <div class="text-[10px] text-slate-600 italic">"{{ Str::limit($review->comments, 40) }}"</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <form action="{{ route('submissions.assign', $work) }}" method="POST" class="flex gap-2">
                                                    @csrf
                                                    <select name="user_id" class="text-[10px] font-bold rounded-lg border-slate-200 py-1 focus:ring-indigo-500 focus:border-indigo-500" required>
                                                        <option value="">Atribuir Avaliador...</option>
                                                        @foreach($reviewers as $rev)
                                                            <option value="{{ $rev->id }}">{{ $rev->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="px-3 py-1 bg-indigo-600 text-white text-[9px] font-black rounded-lg hover:bg-indigo-700 uppercase tracking-widest">OK</button>
                                                </form>
                                            @endif
                                        </td>

                                        {{-- Agenda (RF_F11) --}}
                                        <td class="px-6 py-4">
                                            <form action="{{ route('submissions.schedule', $work) }}" method="POST" class="space-y-2">
                                                @csrf
                                                <div class="grid grid-cols-2 gap-2">
                                                    <input type="datetime-local" name="presentation_date" value="{{ $work->presentation_date ? $work->presentation_date->format('Y-m-d\TH:i') : '' }}" 
                                                           class="text-[9px] font-bold rounded-lg border-slate-200 py-1 w-full" required>
                                                    <input type="text" name="presentation_room" value="{{ $work->presentation_room }}" placeholder="Sala/Link"
                                                           class="text-[9px] font-bold rounded-lg border-slate-200 py-1 w-full" required>
                                                </div>
                                                <div class="flex gap-2">
                                                    <input type="number" name="presentation_order" value="{{ $work->presentation_order }}" placeholder="Ordem"
                                                           class="text-[9px] font-bold rounded-lg border-slate-200 py-1 w-16" required>
                                                    <button type="submit" class="flex-1 bg-slate-800 text-white text-[9px] font-black rounded-lg hover:bg-black uppercase tracking-widest">Agendar</button>
                                                </div>
                                            </form>
                                            @if($work->presentation_date)
                                                <div class="mt-2 p-2 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-bold">
                                                    Agendado: {{ $work->presentation_date->format('d/m/Y H:i') }} | Sala {{ $work->presentation_room }} | #{{ $work->presentation_order }}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Ações --}}
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <a href="{{ route('works.download', $work) }}" class="inline-flex items-center p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm group" title="Baixar Trabalho">
                                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                </a>

                                                {{-- RF_F12: Botão de Confirmar Apresentação --}}
                                                @if($work->presentation_date && !$work->inscription->presented_work)
                                                    <form action="{{ route('submissions.confirm', $work) }}" method="POST" onsubmit="return confirm('Confirmar que o autor realizou a apresentação?');">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-green-600 hover:text-white transition-all">
                                                            Confirmar Presença
                                                        </button>
                                                    </form>
                                                @elseif($work->inscription->presented_work)
                                                    <span class="px-3 py-1.5 bg-green-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest">
                                                        Apresentado ✅
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-black uppercase tracking-widest">Nenhum trabalho submetido ainda</td>
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