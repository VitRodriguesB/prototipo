<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#121214]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10">
                <h3 class="text-3xl font-black text-white uppercase tracking-tight italic pe-4 pr-3">Editar <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Evento</span></h3>
                <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-widest">Atualize as informações do seu evento acadêmico.</p>
            </div>

            <div class="space-y-12">
                {{-- Seção 1: Dados Básicos --}}
                <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-8 sm:p-12 shadow-2xl">
                    <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Sessão: Identificação -->
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] border-b border-white/5 pb-2">Informações Básicas</h4>
                            
                            <div>
                                <x-input-label for="title" :value="__('Título do Evento')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                <x-text-input id="title" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="text" name="title" :value="old('title', $event->title)" required autofocus />
                                <x-input-error :messages="$errors->get('title')" class="mt-2 text-xs font-bold text-red-500" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="location" :value="__('Localização')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                    <x-text-input id="location" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="text" name="location" :value="old('location', $event->location)" required />
                                    <x-input-error :messages="$errors->get('location')" class="mt-2 text-xs font-bold text-red-500" />
                                </div>
                                <div>
                                    <x-input-label for="cover_image" :value="__('Capa do Evento (Banner)')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                    <input id="cover_image" name="cover_image" type="file" class="block w-full text-xs text-slate-500 mt-1
                                        file:me-4 file:py-2 file:px-4
                                        file:rounded-xl file:border-0
                                        file:text-[10px] file:font-black file:uppercase
                                        file:bg-white/5 file:text-indigo-400
                                        hover:file:bg-white/10 transition-all cursor-pointer"
                                    >
                                    @if($event->cover_image_path)
                                        <p class="text-[8px] text-slate-500 mt-2 uppercase font-black tracking-widest">Imagem atual: {{ basename($event->cover_image_path) }}</p>
                                    @endif
                                    <x-input-error :messages="$errors->get('cover_image')" class="mt-2 text-xs font-bold text-red-500" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Descrição Detalhada')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                <textarea id="description" name="description" rows="4" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]">{{ old('description', $event->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2 text-xs font-bold text-red-500" />
                            </div>
                        </div>

                        <!-- Sessão: Prazos e Vagas -->
                        <div class="space-y-6 pt-4">
                            <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] border-b border-white/5 pb-2">Cronograma e Limites</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="event_date" :value="__('Data da Realização')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                    <x-text-input id="event_date" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="datetime-local" name="event_date" :value="old('event_date', $event->event_date->format('Y-m-d\TH:i'))" required />
                                    <x-input-error :messages="$errors->get('event_date')" class="mt-2 text-xs font-bold text-red-500" />
                                </div>
                                <div>
                                    <x-input-label for="registration_deadline" :value="__('Fim das Inscrições')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                    <x-text-input id="registration_deadline" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="datetime-local" name="registration_deadline" :value="old('registration_deadline', $event->registration_deadline->format('Y-m-d\TH:i'))" required />
                                    <x-input-error :messages="$errors->get('registration_deadline')" class="mt-2 text-xs font-bold text-red-500" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="max_participants" :value="__('Capacidade Máxima')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                <x-text-input id="max_participants" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="number" name="max_participants" :value="old('max_participants', $event->max_participants)" />
                                <x-input-error :messages="$errors->get('max_participants')" class="mt-2 text-xs font-bold text-red-500" />
                            </div>
                        </div>

                        <!-- Sessão: Financeiro -->
                        <div class="space-y-6 pt-4">
                            <h4 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] border-b border-white/5 pb-2">Financeiro e Pagamento</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="registration_fee" :value="__('Taxa de Inscrição Base (R$)')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                    <x-text-input id="registration_fee" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="number" name="registration_fee" :value="old('registration_fee', $event->registration_fee)" step="0.01" min="0" required />
                                    <x-input-error :messages="$errors->get('registration_fee')" class="mt-2 text-xs font-bold text-red-500" />
                                </div>
                                <div>
                                    <x-input-label for="pix_key" :value="__('Chave Pix (Recebimento)')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                    <x-text-input id="pix_key" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="text" name="pix_key" :value="old('pix_key', $event->pix_key)" required />
                                    <x-input-error :messages="$errors->get('pix_key')" class="mt-2 text-xs font-bold text-red-500" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-10 border-t border-white/5">
                            <a href="{{ route('events.index') }}" class="text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-10 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-2xl shadow-2xl hover:scale-105 transition-all uppercase tracking-widest text-[10px]">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Seção 2: Tipos de Inscrição (RF_F5) --}}
                <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-8 sm:p-12 shadow-2xl">
                    <div class="flex items-center justify-between mb-8 border-b border-white/5 pb-4">
                        <div>
                            <h4 class="text-xl font-black text-white uppercase italic">Tipos de <span class="text-indigo-500">Inscrição</span></h4>
                            <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mt-1">Gerencie as modalidades de participação e submissão.</p>
                        </div>
                        <a href="{{ route('inscription_types.create', $event) }}" class="px-6 py-3 bg-white/5 text-indigo-400 border border-indigo-500/20 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition-all">
                            + Adicionar Tipo
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($event->inscriptionTypes as $type)
                            <div class="flex items-center justify-between p-6 bg-[#121214] border border-white/5 rounded-2xl hover:border-indigo-500/30 transition-all group">
                                <div class="flex items-center gap-6">
                                    <div class="w-12 h-12 bg-indigo-500/10 text-indigo-400 rounded-xl flex items-center justify-center font-black">
                                        {{ substr($type->type, 0, 1) }}
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-black text-white uppercase tracking-widest">{{ $type->type }}</h5>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-[10px] text-slate-500 font-bold">R$ {{ number_format($type->price, 2, ',', '.') }}</span>
                                            @if($type->allow_work_submission)
                                                <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 rounded text-[8px] font-black uppercase tracking-tighter border border-emerald-500/20">Permite Trabalhos</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('inscription_types.edit', $type) }}" class="p-2 bg-white/5 text-indigo-400 hover:bg-indigo-500/20 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form action="{{ route('inscription_types.destroy', $type) }}" method="POST" onsubmit="return confirm('Excluir este tipo de inscrição?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-white/5 text-red-400 hover:bg-red-500/20 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 border border-dashed border-white/5 rounded-2xl">
                                <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.3em]">Nenhum tipo de inscrição cadastrado.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Seção 3: Atividades (RF_F8 / RF_F9) --}}
                <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-8 sm:p-12 shadow-2xl">
                    <div class="flex items-center justify-between mb-8 border-b border-white/5 pb-4">
                        <div>
                            <h4 class="text-xl font-black text-white uppercase italic">Grade de <span class="text-purple-500">Atividades</span></h4>
                            <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mt-1">Defina o cronograma de palestras, minicursos e oficinas.</p>
                        </div>
                        <a href="{{ route('activities.create', $event) }}" class="px-6 py-3 bg-white/5 text-purple-400 border border-purple-500/20 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-purple-500 hover:text-white transition-all">
                            + Adicionar Atividade
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($event->activities->sortBy('start_time') as $activity)
                            <div class="flex items-center justify-between p-6 bg-[#121214] border border-white/5 rounded-2xl hover:border-purple-500/30 transition-all group">
                                <div class="flex items-center gap-6">
                                    <div class="text-center border-r border-white/5 pr-6">
                                        <span class="block text-lg font-black text-white leading-none">{{ $activity->start_time->format('H:i') }}</span>
                                        <span class="text-[8px] font-black text-purple-500 uppercase mt-1 block">Início</span>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-black text-white uppercase tracking-widest">{{ $activity->title }}</h5>
                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">
                                            {{ $activity->location }} • {{ $activity->max_participants ? $activity->max_participants . ' Vagas' : 'Ilimitado' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('activities.edit', $activity) }}" class="p-2 bg-white/5 text-slate-400 hover:text-white rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Excluir esta atividade?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-white/5 text-slate-400 hover:text-red-500 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 border border-dashed border-white/5 rounded-2xl">
                                <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.3em]">Nenhuma atividade cadastrada na agenda.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
