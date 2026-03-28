<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#121214]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10">
                <h2 class="text-3xl font-black text-white uppercase tracking-tight italic pr-3">
                    EDITAR <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600">ATIVIDADE</span>
                </h2>
                <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-widest italic">Atividade: {{ $activity->title }}</p>
            </div>

            <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-8 sm:p-10 shadow-2xl">
                <form method="POST" action="{{ route('activities.update', $activity->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Título -->
                    <div>
                        <label for="title" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Título da Atividade (ex: Palestra, Minicurso)</label>
                        <input id="title" type="text" name="title" value="{{ old('title', $activity->title) }}" required autofocus
                            class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all placeholder-slate-600 shadow-inner"
                            placeholder="Ex: Workshop de Laravel & Alpine.js">
                        <x-input-error :messages="$errors->get('title')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="description" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Descrição</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all placeholder-slate-600 shadow-inner"
                            placeholder="Conte um pouco sobre o que será abordado nesta atividade...">{{ old('description', $activity->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Local -->
                        <div>
                            <label for="location" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Local (ex: Auditório, Sala 10)</label>
                            <input id="location" type="text" name="location" value="{{ old('location', $activity->location) }}"
                                class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all placeholder-slate-600 shadow-inner"
                                placeholder="Local físico ou link virtual">
                            <x-input-error :messages="$errors->get('location')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                        </div>

                        <!-- Máx. Participantes -->
                        <div>
                            <label for="max_participants" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Máximo de Participantes</label>
                            <input id="max_participants" type="number" name="max_participants" value="{{ old('max_participants', $activity->max_participants) }}"
                                class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all placeholder-slate-600 shadow-inner"
                                placeholder="Vazio para ilimitado">
                            <x-input-error :messages="$errors->get('max_participants')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Data/Hora de Início -->
                        <div>
                            <label for="start_time" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Data e Hora de Início</label>
                            <input id="start_time" type="datetime-local" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($activity->start_time)->format('Y-m-d\TH:i')) }}" required
                                class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all shadow-inner">
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                        </div>

                        <!-- Data/Hora de Fim -->
                        <div>
                            <label for="end_time" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Data e Hora de Fim</label>
                            <input id="end_time" type="datetime-local" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($activity->end_time)->format('Y-m-d\TH:i')) }}" required
                                class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all shadow-inner">
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                        </div>
                    </div>

                    <div class="flex justify-end items-center gap-6 mt-8 pt-6 border-t border-white/5">
                        <a href="{{ route('events.edit', $activity->event_id) }}" class="text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-white transition-colors">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-purple-500/20 hover:scale-[1.02] transition-all">
                            {{ __('Atualizar Atividade') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
