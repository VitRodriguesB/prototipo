<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white tracking-tighter uppercase italic">
            {{ __('Avaliar Trabalho') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#121214] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Card Principal Dark -->
            <div class="bg-[#0a0a0a] overflow-hidden shadow-2xl rounded-2xl border border-white/5">
                <div class="p-6 md:p-10 text-white">

                    <!-- Detalhes do Trabalho -->
                    <div class="border-b border-white/5 pb-8">
                        <div class="mb-6">
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-2xl text-[10px] font-black uppercase tracking-widest mb-3 inline-block border border-indigo-500/20">
                                Trabalho Científico
                            </span>
                            <h3 class="text-3xl font-black text-white leading-tight tracking-tighter uppercase italic">{{ $review->work->title }}</h3>
                            <p class="text-xs text-slate-400 mt-2 font-bold uppercase tracking-widest italic">Autor: <span class="text-indigo-400">{{ $review->work->user->name }}</span></p>
                        </div>
                        
                        <div class="mt-8 bg-white/5 p-6 rounded-2xl border border-white/5 shadow-inner">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Resumo (Abstract)</h4>
                            <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line italic">"{{ $review->work->abstract }}"</p>
                        </div>

                        <div class="mt-8 flex justify-center sm:justify-start">
                            <a href="{{ route('works.download', $review->work) }}" class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white bg-white/5 border border-white/10 rounded-2xl shadow-xl hover:bg-white/10 transition-all hover:scale-105 group">
                                <svg class="w-5 h-5 text-indigo-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Baixar Trabalho (PDF/DOC)
                            </a>
                        </div>
                    </div>

                    <!-- Formulário de Avaliação -->
                    <form method="POST" action="{{ route('reviews.update', $review) }}" class="mt-10 space-y-8">
                        @csrf
                        @method('PATCH')

                        <!-- Status (Aprovado/Reprovado) -->
                        <div>
                            <label for="status" class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-3 block ms-4">Parecer da Avaliação</label>
                            <select id="status" name="status" class="block w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-white text-xs font-bold uppercase tracking-wider py-4 px-6" required>
                                <option value="" disabled selected>Selecione um parecer oficial...</option>
                                <option value="1">Aprovado para Apresentação</option>
                                <option value="2">Reprovado / Requer Ajustes</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                        </div>

                        <!-- Comentários -->
                        <div>
                            <label for="comments" class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-3 block ms-4">Comentários (Justificativa para o autor)</label>
                            <textarea id="comments" name="comments" rows="6" class="block w-full bg-[#121214] border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-white text-sm py-4 px-6 placeholder:text-slate-600 shadow-inner" placeholder="Descreva os pontos positivos e melhorias necessárias..." required>{{ old('comments') }}</textarea>
                            <x-input-error :messages="$errors->get('comments')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-8 border-t border-white/5">
                            <a href="{{ route('dashboard') }}" class="text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">
                                {{ __('Cancelar e Voltar') }}
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-10 py-5 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white font-black rounded-2xl shadow-2xl hover:scale-105 transition-all uppercase tracking-widest text-[10px]">
                                {{ __('Enviar Avaliação Oficial') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
