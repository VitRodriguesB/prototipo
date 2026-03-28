<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#121214]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-[#0a0a0a] overflow-hidden shadow-2xl rounded-2xl border border-white/5">
                <div class="p-6 sm:p-12 text-[#f0eee9]">
                    
                    <div class="mb-10">
                        <h3 class="text-3xl font-black text-white uppercase tracking-tight italic pe-4">Configuração do <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#4f46e5] to-[#9333ea]">Evento</span></h3>
                        <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-widest">Preencha os detalhes para publicar seu evento no portal.</p>
                    </div>

                    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Sessão: Identificação -->
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] border-b border-white/5 pb-2">Informações Básicas</h4>
                            
                            <div>
                                <x-input-label for="title" :value="__('Título do Evento')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                <x-text-input id="title" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="text" name="title" :value="old('title')" required autofocus placeholder="Ex: Simpósio de Tecnologia 2026" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2 text-xs font-bold text-red-500" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="location" :value="__('Localização')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                    <x-text-input id="location" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="text" name="location" :value="old('location')" required placeholder="Ex: Auditório Principal ou Online" />
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
                                    <x-input-error :messages="$errors->get('cover_image')" class="mt-2 text-xs font-bold text-red-500" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Descrição Detalhada')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                <textarea id="description" name="description" rows="4" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" placeholder="Conte mais sobre o que acontecerá no evento...">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2 text-xs font-bold text-red-500" />
                            </div>
                        </div>

                        <!-- Sessão: Prazos e Vagas -->
                        <div class="space-y-6 pt-4">
                            <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] border-b border-white/5 pb-2">Cronograma e Limites</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="event_date" :value="__('Data da Realização')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                    <x-text-input id="event_date" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="datetime-local" name="event_date" :value="old('event_date')" required />
                                    <x-input-error :messages="$errors->get('event_date')" class="mt-2 text-xs font-bold text-red-500" />
                                </div>
                                <div>
                                    <x-input-label for="registration_deadline" :value="__('Fim das Inscrições')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                    <x-text-input id="registration_deadline" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="datetime-local" name="registration_deadline" :value="old('registration_deadline')" required />
                                    <x-input-error :messages="$errors->get('registration_deadline')" class="mt-2 text-xs font-bold text-red-500" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="max_participants" :value="__('Capacidade Máxima')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                <x-text-input id="max_participants" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="number" name="max_participants" :value="old('max_participants')" placeholder="Deixe vazio para ilimitado" />
                                <x-input-error :messages="$errors->get('max_participants')" class="mt-2 text-xs font-bold text-red-500" />
                            </div>
                        </div>

                        <!-- Sessão: Financeiro -->
                        <div class="space-y-6 pt-4">
                            <h4 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] border-b border-white/5 pb-2">Financeiro e Pagamento</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="registration_fee" :value="__('Taxa de Inscrição Base (R$)')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                    <x-text-input id="registration_fee" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="number" name="registration_fee" :value="old('registration_fee', 0)" step="0.01" min="0" required />
                                    <x-input-error :messages="$errors->get('registration_fee')" class="mt-2 text-xs font-bold text-red-500" />
                                </div>
                                <div>
                                    <x-input-label for="pix_key" :value="__('Chave Pix (Recebimento)')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                                    <x-text-input id="pix_key" class="block mt-1 w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-[#f0eee9]" type="text" name="pix_key" :value="old('pix_key')" required placeholder="E-mail, CPF, CNPJ ou Aleatória" />
                                    <x-input-error :messages="$errors->get('pix_key')" class="mt-2 text-xs font-bold text-red-500" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-10 border-t border-white/5">
                            <a href="{{ route('events.index') }}" class="text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-10 py-4 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white font-black rounded-2xl shadow-2xl hover:scale-105 transition-all uppercase tracking-widest text-[10px]">
                                Criar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>