<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#121214]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10">
                <h2 class="text-3xl font-black text-white uppercase tracking-tight italic pr-3">
                    SUBMETER <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">TRABALHO</span>
                </h2>
                <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-widest italic">Evento: {{ $event->title }}</p>
            </div>

            <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-8 sm:p-10 shadow-2xl">
                <!-- Formulário precisa de enctype para upload -->
                <form method="POST" action="{{ route('works.store', $event) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Título do Trabalho -->
                    <div>
                        <label for="title" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Título do Trabalho</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus
                            class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600 shadow-inner"
                            placeholder="Ex: Análise de Performance em Sistemas Distribuídos">
                        <x-input-error :messages="$errors->get('title')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tipo de Trabalho (Dropdown) -->
                        <div>
                            <label for="work_type_id" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Tipo de Trabalho</label>
                            <select id="work_type_id" name="work_type_id" required
                                class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner">
                                <option value="" disabled selected>Selecione a modalidade...</option>
                                @foreach($workTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('work_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->type }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('work_type_id')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                        </div>

                        <!-- Orientador -->
                        <div>
                            <label for="advisor" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Nome do Orientador</label>
                            <input id="advisor" type="text" name="advisor" value="{{ old('advisor') }}" required
                                class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600 shadow-inner"
                                placeholder="Nome completo do docente">
                            <x-input-error :messages="$errors->get('advisor')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                        </div>
                    </div>

                    <!-- Coautores -->
                    <div>
                        <label for="co_authors_text" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Coautores (separados por vírgula)</label>
                        <input id="co_authors_text" type="text" name="co_authors_text" value="{{ old('co_authors_text') }}"
                            class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600 shadow-inner"
                            placeholder="Ex: João Silva, Maria Santos">
                        <x-input-error :messages="$errors->get('co_authors_text')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                    </div>
                    
                    <!-- Resumo (Abstract) -->
                    <div>
                        <label for="abstract" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Resumo (Abstract)</label>
                        <textarea id="abstract" name="abstract" rows="6" required
                            class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600 shadow-inner"
                            placeholder="Descreva brevemente os objetivos, metodologia e resultados do trabalho...">{{ old('abstract') }}</textarea>
                        <x-input-error :messages="$errors->get('abstract')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                    </div>

                    <!-- Upload do Ficheiro -->
                    <div>
                        <label for="file" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Ficheiro do Trabalho (PDF, DOC, DOCX - Máx 5MB)</label>
                        <input id="file" name="file" type="file" required 
                            class="block w-full text-slate-400 text-sm border border-white/10 rounded-xl bg-[#121214] p-2
                            file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 
                            file:text-[10px] file:font-black file:uppercase file:tracking-widest 
                            file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 transition-all cursor-pointer">
                        <x-input-error :messages="$errors->get('file')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                    </div>

                    <div class="flex justify-end items-center gap-6 mt-8 pt-6 border-t border-white/5">
                        <a href="{{ route('dashboard') }}" class="text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-white transition-colors">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="px-8 py-4 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:scale-[1.02] transition-all">
                            {{ __('Submeter Trabalho') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
