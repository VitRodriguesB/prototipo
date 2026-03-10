<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Novo Evento Acadêmico') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl rounded-[2.5rem] border border-slate-100">
                <div class="p-6 sm:p-12 text-slate-900">
                    
                    <div class="mb-10">
                        <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">Configuração do Evento</h3>
                        <p class="text-sm text-slate-500 mt-1 font-medium">Preencha os detalhes para publicar seu evento no portal.</p>
                    </div>

                    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Sessão: Identificação -->
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] border-b pb-2">Informações Básicas</h4>
                            
                            <div>
                                <x-input-label for="title" :value="__('Título do Evento')" class="font-bold text-slate-700" />
                                <x-text-input id="title" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="text" name="title" :value="old('title')" required autofocus placeholder="Ex: Simpósio de Tecnologia 2026" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="location" :value="__('Localização')" class="font-bold text-slate-700" />
                                    <x-text-input id="location" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="text" name="location" :value="old('location')" required placeholder="Ex: Auditório Principal ou Online" />
                                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="cover_image" :value="__('Capa do Evento (Banner)')" class="font-bold text-slate-700" />
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

                            <div>
                                <x-input-label for="description" :value="__('Descrição Detalhada')" class="font-bold text-slate-700" />
                                <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-[1.5rem] shadow-sm" placeholder="Conte mais sobre o que acontecerá no evento...">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Sessão: Prazos e Vagas -->
                        <div class="space-y-6 pt-4">
                            <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] border-b pb-2">Cronograma e Limites</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="event_date" :value="__('Data da Realização')" class="font-bold text-slate-700" />
                                    <x-text-input id="event_date" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="datetime-local" name="event_date" :value="old('event_date')" required />
                                    <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="registration_deadline" :value="__('Fim das Inscrições')" class="font-bold text-slate-700" />
                                    <x-text-input id="registration_deadline" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="datetime-local" name="registration_deadline" :value="old('registration_deadline')" required />
                                    <x-input-error :messages="$errors->get('registration_deadline')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="max_participants" :value="__('Capacidade Máxima')" class="font-bold text-slate-700" />
                                <x-text-input id="max_participants" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="number" name="max_participants" :value="old('max_participants')" placeholder="Deixe vazio para ilimitado" />
                                <x-input-error :messages="$errors->get('max_participants')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Sessão: Financeiro -->
                        <div class="space-y-6 pt-4">
                            <h4 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] border-b pb-2">Financeiro e Pagamento</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="registration_fee" :value="__('Taxa de Inscrição Base (R$)')" class="font-bold text-slate-700" />
                                    <x-text-input id="registration_fee" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="number" name="registration_fee" :value="old('registration_fee', 0)" step="0.01" min="0" required />
                                    <x-input-error :messages="$errors->get('registration_fee')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="pix_key" :value="__('Chave Pix (Recebimento)')" class="font-bold text-slate-700" />
                                    <x-text-input id="pix_key" class="block mt-1 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm" type="text" name="pix_key" :value="old('pix_key')" required placeholder="E-mail, CPF, CNPJ ou Aleatória" />
                                    <x-input-error :messages="$errors->get('pix_key')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-10 border-t border-slate-100">
                            <a href="{{ route('events.index') }}" class="text-xs font-black text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-10 py-4 bg-slate-900 text-white font-black rounded-2xl shadow-2xl hover:bg-black transition-all active:scale-95 uppercase tracking-widest text-xs">
                                Criar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>