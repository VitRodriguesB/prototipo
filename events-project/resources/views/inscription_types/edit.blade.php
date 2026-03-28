<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#121214]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10">
                <h2 class="text-3xl font-black text-white uppercase tracking-tight italic pr-3">
                    EDITAR <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">TIPO DE INSCRIÇÃO</span>
                </h2>
                <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-widest italic">Tipo: {{ $inscriptionType->type }}</p>
            </div>

            <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-8 sm:p-10 shadow-2xl">
                <form method="POST" action="{{ route('inscription_types.update', $inscriptionType->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Tipo (Nome) -->
                    <div>
                        <label for="type" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Nome do Tipo (ex: Ouvinte, Autor, Palestrante)</label>
                        <input id="type" type="text" name="type" value="{{ old('type', $inscriptionType->type) }}" required autofocus
                            class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600 shadow-inner"
                            placeholder="Ex: Autor de Trabalho">
                        <x-input-error :messages="$errors->get('type')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                    </div>

                    <!-- Preço (R$) -->
                    <div>
                        <label for="price" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Preço da Inscrição (R$)</label>
                        <input id="price" type="number" name="price" value="{{ old('price', $inscriptionType->price) }}" required step="0.01" min="0"
                            class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner"
                            placeholder="0,00">
                        <x-input-error :messages="$errors->get('price')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                    </div>

                    <!-- Checkbox para Submissão de Trabalho -->
                    <div class="p-6 bg-[#121214] border border-white/5 rounded-xl">
                        <label for="allow_work_submission" class="inline-flex items-center cursor-pointer group">
                            <div class="relative">
                                <input id="allow_work_submission" type="checkbox" class="sr-only peer" name="allow_work_submission" value="1" {{ old('allow_work_submission', $inscriptionType->allow_work_submission) ? 'checked' : '' }}>
                                <div class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600 peer-checked:after:bg-white"></div>
                            </div>
                            <span class="ms-4 text-xs font-black text-slate-400 uppercase tracking-widest group-hover:text-white transition-colors">{{ __('Permitir submissão de trabalho acadêmico?') }}</span>
                        </label>
                        <p class="mt-2 text-[9px] text-slate-600 font-bold uppercase tracking-tighter ml-14">Se marcado, participantes deste tipo poderão enviar arquivos PDF/DOCX.</p>
                        <x-input-error :messages="$errors->get('allow_work_submission')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                    </div>

                    <div class="flex justify-end items-center gap-6 mt-8 pt-6 border-t border-white/5">
                        <a href="{{ route('events.edit', $inscriptionType->event_id) }}" class="text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-white transition-colors">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="px-8 py-4 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:scale-[1.02] transition-all">
                            {{ __('Atualizar Tipo de Inscrição') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
