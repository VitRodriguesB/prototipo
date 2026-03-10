<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Editar Evento Acadêmico') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- CARD 1: INFORMAÇÕES GERAIS -->
            <div class="bg-white overflow-hidden shadow-xl rounded-[2.5rem] border border-slate-100">
                <div class="p-6 sm:p-12 text-slate-900">
                    
                    <div class="mb-10 flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">Detalhes do Evento</h3>
                            <p class="text-sm text-slate-500 mt-1 font-medium">Gerencie as informações principais e a capa do evento.</p>
                        </div>
                        <span class="px-4 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-indigo-100">ID: #{{ $event->id }}</span>
                    </div>

                    <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Sessão: Identificação -->
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] border-b pb-2">Informações Básicas</h4>
                            
                            <div>
                                <x-input-label for="title" :value="__('Título do Evento')" class="font-bold text-slate-700" />
                                <x-text-input id="title" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="text" name="title" :value="old('title', $event->title)" required autofocus />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="location" :value="__('Localização')" class="font-bold text-slate-700" />
                                    <x-text-input id="location" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="text" name="location" :value="old('location', $event->location)" required />
                                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="cover_image" :value="__('Capa do Evento (Substituir)')" class="font-bold text-slate-700" />
                                    <input id="cover_image" name="cover_image" type="file" class="block w-full text-xs text-slate-500 mt-1
                                        file:me-4 file:py-2 file:px-4
                                        file:rounded-xl file:border-0
                                        file:text-[10px] file:font-black file:uppercase
                                        file:bg-indigo-50 file:text-indigo-600
                                        hover:file:bg-indigo-100 transition-all cursor-pointer"
                                    >
                                    <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />
                                </div>
                            </div>

                            @if ($event->cover_image_path)
                                <div class="p-4 bg-slate-50 rounded-3xl border border-slate-100">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Capa Atual</p>
                                    <img src="{{ asset('storage/' . $event->cover_image_path) }}" alt="Capa" class="w-full h-40 object-cover rounded-2xl shadow-md">
                                </div>
                            @endif

                            <div>
                                <x-input-label for="description" :value="__('Descrição Detalhada')" class="font-bold text-slate-700" />
                                <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-[1.5rem] shadow-sm">{{ old('description', $event->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Sessão: Prazos e Vagas -->
                        <div class="space-y-6 pt-4">
                            <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] border-b pb-2">Cronograma e Limites</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="event_date" :value="__('Data da Realização')" class="font-bold text-slate-700" />
                                    <x-text-input id="event_date" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="datetime-local" name="event_date" :value="old('event_date', $event->event_date->format('Y-m-d\TH:i'))" required />
                                    <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="registration_deadline" :value="__('Fim das Inscrições')" class="font-bold text-slate-700" />
                                    <x-text-input id="registration_deadline" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="datetime-local" name="registration_deadline" :value="old('registration_deadline', $event->registration_deadline->format('Y-m-d\TH:i'))" required />
                                    <x-input-error :messages="$errors->get('registration_deadline')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="max_participants" :value="__('Capacidade Máxima')" class="font-bold text-slate-700" />
                                <x-text-input id="max_participants" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="number" name="max_participants" :value="old('max_participants', $event->max_participants)" placeholder="Deixe vazio para ilimitado" />
                                <x-input-error :messages="$errors->get('max_participants')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Sessão: Financeiro -->
                        <div class="space-y-6 pt-4">
                            <h4 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] border-b pb-2">Financeiro e Pagamento</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="registration_fee" :value="__('Taxa de Inscrição Base (R$)')" class="font-bold text-slate-700" />
                                    <x-text-input id="registration_fee" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="number" name="registration_fee" :value="old('registration_fee', $event->registration_fee)" step="0.01" min="0" required />
                                    <x-input-error :messages="$errors->get('registration_fee')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="pix_key" :value="__('Chave Pix (Recebimento)')" class="font-bold text-slate-700" />
                                    <x-text-input id="pix_key" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="text" name="pix_key" :value="old('pix_key', $event->pix_key)" required />
                                    <x-input-error :messages="$errors->get('pix_key')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-10 border-t border-slate-100">
                            <a href="{{ route('events.index') }}" class="text-xs font-black text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">
                                Cancelar Alterações
                            </a>
                            <button type="submit" class="px-10 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-2xl hover:bg-indigo-700 transition-all active:scale-95 uppercase tracking-widest text-xs">
                                Atualizar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- CARD 2: MODALIDADES -->
            <div class="bg-white overflow-hidden shadow-xl rounded-[2.5rem] border border-slate-100 p-6 sm:p-12">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Modalidades de Inscrição</h3>
                        <p class="text-sm text-slate-500 mt-1 font-medium">Defina os tipos de participantes (Autor, Ouvinte, etc).</p>
                    </div>
                    <a href="{{ route('inscription_types.create', $event) }}" class="px-6 py-3 bg-emerald-50 text-emerald-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-100 transition-all border border-emerald-100">
                        + Nova Modalidade
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse ($event->inscriptionTypes as $type)
                        <div class="p-6 border-2 border-slate-50 rounded-3xl bg-slate-50/30 flex justify-between items-center group hover:border-emerald-200 transition-all">
                            <div>
                                <h4 class="font-black text-slate-800">{{ $type->type }}</h4>
                                <p class="text-sm font-bold text-emerald-600">R$ {{ number_format($type->price, 2, ',', '.') }}</p>
                                @if($type->allow_work_submission)
                                    <span class="text-[8px] font-black text-indigo-400 uppercase tracking-widest">Permite Trabalho</span>
                                @endif
                            </div>
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('inscription_types.edit', $type) }}" class="p-2 text-slate-400 hover:text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                <form action="{{ route('inscription_types.destroy', $type) }}" method="POST" onsubmit="return confirm('Excluir esta modalidade?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center py-8 text-slate-400 text-xs font-bold uppercase tracking-widest">Nenhuma modalidade cadastrada.</p>
                    @endforelse
                </div>
            </div>

            <!-- CARD 3: ATIVIDADES -->
            <div class="bg-white overflow-hidden shadow-xl rounded-[2.5rem] border border-slate-100 p-6 sm:p-12 text-slate-900">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Cronograma de Atividades</h3>
                        <p class="text-sm text-slate-500 mt-1 font-medium">Palestras, Workshops e Mesas Redondas.</p>
                    </div>
                    <a href="{{ route('activities.create', $event) }}" class="px-6 py-3 bg-indigo-50 text-indigo-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-100 transition-all border border-indigo-100">
                        + Nova Atividade
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse ($event->activities->sortBy('start_time') as $activity)
                        <div class="p-6 border-2 border-slate-50 rounded-3xl bg-slate-50/30 flex justify-between items-center group hover:border-indigo-200 transition-all">
                            <div class="flex gap-4 items-center">
                                <div class="text-center p-3 bg-white rounded-2xl shadow-sm border border-slate-100">
                                    <span class="block text-xs font-black text-indigo-600 leading-none">{{ $activity->start_time->format('H:i') }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase mt-1">Início</span>
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-800">{{ $activity->title }}</h4>
                                    <p class="text-xs text-slate-500">{{ $activity->location }} • {{ $activity->max_participants ?? 'Ilimitado' }} vagas</p>
                                </div>
                            </div>
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('activities.edit', $activity) }}" class="p-2 text-slate-400 hover:text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Excluir esta atividade?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-8 text-slate-400 text-xs font-bold uppercase tracking-widest">Nenhuma atividade cadastrada para este evento.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>