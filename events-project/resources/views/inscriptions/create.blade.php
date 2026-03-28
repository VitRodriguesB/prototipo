<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#121214]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10">
                <h2 class="text-3xl font-black text-white uppercase tracking-tight italic pr-3">
                    FINALIZAR <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">INSCRIÇÃO</span>
                </h2>
                <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-widest italic">Evento: {{ $event->title }}</p>
            </div>

            <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-8 sm:p-10 shadow-2xl">
                <form method="POST" action="{{ route('inscriptions.store', $event->id) }}" class="space-y-8">
                    @csrf

                    <!-- Passo 1: Modalidade -->
                    <div>
                        <label for="inscription_type_id" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 block border-b border-white/5 pb-2">1. Selecione sua Modalidade</label>
                        
                        @if ($inscriptionTypes->isEmpty())
                            <div class="p-6 bg-red-500/10 border border-red-500/20 rounded-xl text-center">
                                <p class="text-sm font-bold text-red-400 uppercase tracking-widest">O organizador ainda não cadastrou modalidades de inscrição.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 gap-4">
                                @foreach ($inscriptionTypes as $type)
                                    <label class="relative flex items-center p-6 bg-[#121214] border border-white/5 rounded-2xl cursor-pointer hover:border-indigo-500/30 transition-all group">
                                        <input type="radio" name="inscription_type_id" value="{{ $type->id }}" class="sr-only peer" required>
                                        <div class="w-5 h-5 border-2 border-white/10 rounded-full flex items-center justify-center peer-checked:border-indigo-500 transition-all">
                                            <div class="w-2.5 h-2.5 bg-indigo-500 rounded-full opacity-0 peer-checked:opacity-100 transition-all"></div>
                                        </div>
                                        <div class="ms-6 flex-1">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-black text-white uppercase tracking-widest group-hover:text-indigo-400 transition-colors">{{ $type->type }}</span>
                                                <span class="text-lg font-black text-white italic">R$ {{ number_format($type->price, 2, ',', '.') }}</span>
                                            </div>
                                            @if($type->allow_work_submission)
                                                <span class="mt-1 inline-block text-[8px] font-black text-indigo-500 uppercase tracking-tighter bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-500/20">Permite submissão de trabalho acadêmico</span>
                                            @endif
                                        </div>
                                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-indigo-500/50 rounded-2xl pointer-events-none transition-all"></div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                        <x-input-error :messages="$errors->get('inscription_type_id')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                    </div>

                    <!-- Passo 2: Atividades -->
                    <div class="pt-4">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 block border-b border-white/5 pb-2">2. Escolha suas Atividades (Opcional)</label>
                        <p class="text-[10px] text-slate-600 font-bold uppercase tracking-widest mb-6">Atividades sem limite de vagas já estão inclusas automaticamente.</p>

                        <div class="space-y-4">
                            @php 
                                $automaticActivities = $event->activities->where('max_participants', null);
                                $optionalActivities = $event->activities->where('max_participants', '!=', null);
                            @endphp

                            @if($automaticActivities->count() > 0)
                                <div class="p-6 bg-white/5 rounded-2xl border border-white/5">
                                    <h4 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-4">Inscrição Automática nestas atividades:</h4>
                                    <ul class="space-y-3">
                                        @foreach($automaticActivities as $act)
                                            <li class="flex items-center gap-3">
                                                <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></div>
                                                <span class="text-xs font-bold text-slate-300 uppercase tracking-tight">{{ $act->title }}</span>
                                                <span class="text-[10px] text-slate-600 italic">• {{ \Carbon\Carbon::parse($act->start_time)->format('H:i') }}h</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($optionalActivities->count() > 0)
                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($optionalActivities as $act)
                                        @php 
                                            $remaining = $act->max_participants - $act->participants()->count();
                                            $isFull = $remaining <= 0;
                                        @endphp
                                        <label class="relative flex items-center p-5 bg-[#121214] border border-white/5 rounded-2xl transition-all {{ $isFull ? 'opacity-40 grayscale cursor-not-allowed' : 'cursor-pointer hover:border-purple-500/30 group' }}">
                                            <input type="checkbox" name="activities[]" value="{{ $act->id }}" class="sr-only peer" {{ $isFull ? 'disabled' : '' }}>
                                            <div class="w-5 h-5 border-2 border-white/10 rounded-lg flex items-center justify-center peer-checked:border-purple-500 peer-checked:bg-purple-500 transition-all">
                                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <div class="ms-5 flex-1">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs font-black text-white uppercase tracking-widest group-hover:text-purple-400 transition-colors">{{ $act->title }}</span>
                                                    <span class="text-[9px] font-black uppercase px-3 py-1 rounded-full border {{ $isFull ? 'bg-red-500/10 text-red-500 border-red-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' }}">
                                                        {{ $isFull ? 'ESGOTADO' : $remaining . ' VAGAS' }}
                                                    </span>
                                                </div>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter mt-1">{{ \Carbon\Carbon::parse($act->start_time)->format('d/m \à\s H:i') }}h — {{ $act->location }}</p>
                                            </div>
                                            <div class="absolute inset-0 border-2 border-transparent peer-checked:border-purple-500/50 rounded-2xl pointer-events-none transition-all"></div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            @if($event->activities->count() == 0)
                                <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest text-center py-6 border border-dashed border-white/5 rounded-2xl">Nenhuma atividade agendada.</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end items-center gap-6 mt-10 pt-8 border-t border-white/5">
                        <a href="{{ route('events.public.show', $event->id) }}" class="text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-white transition-colors">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="px-10 py-5 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-indigo-500/20 hover:scale-[1.02] transition-all disabled:opacity-50 disabled:hover:scale-100" :disabled="{{ $inscriptionTypes->isEmpty() ? 'true' : 'false' }}">
                            {{ __('Confirmar Minha Inscrição') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
