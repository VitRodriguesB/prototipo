<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-white tracking-tighter uppercase italic">
                {{ __('Gestão de Eventos') }}
            </h2>
            <a href="{{ route('events.create') }}" class="px-6 py-3 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:scale-105 transition-all">
                + Criar Novo Evento
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#121214]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-[#0a0a0a] border border-white/5 shadow-2xl rounded-[2.5rem] overflow-hidden">
                <div class="p-6 sm:p-10">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/5">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Evento</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Data / Local</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Inscritos</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-slate-500 uppercase tracking-widest">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse ($events as $event)
                                    <tr class="hover:bg-white/5 transition-colors group">
                                        <td class="px-6 py-6">
                                            <div class="flex items-center gap-4">
                                                @if($event->cover_image_path)
                                                    <img src="{{ asset('storage/' . $event->cover_image_path) }}" class="w-12 h-12 rounded-xl object-cover grayscale group-hover:grayscale-0 transition-all">
                                                @else
                                                    <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center">
                                                        <x-application-logo class="w-6 h-6 text-white/20" />
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-black text-white uppercase italic">{{ $event->title }}</div>
                                                    <div class="text-[10px] font-bold text-indigo-400 mt-1 uppercase tracking-widest">R$ {{ number_format($event->registration_fee, 2, ',', '.') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6">
                                            <div class="text-xs font-bold text-slate-300">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y H:i') }}</div>
                                            <div class="text-[10px] text-slate-500 mt-1 uppercase tracking-tighter">{{ $event->location }}</div>
                                        </td>
                                        <td class="px-6 py-6">
                                            <div class="inline-flex items-center px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-black text-white">
                                                {{ $event->inscriptions()->count() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('events.edit', $event) }}" class="p-3 bg-white/5 text-slate-400 hover:bg-indigo-600 hover:text-white rounded-xl transition-all" title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <a href="{{ route('events.export', $event) }}" class="p-3 bg-white/5 text-emerald-500 hover:bg-emerald-600 hover:text-white rounded-xl transition-all" title="Exportar CSV">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </a>
                                                <form method="POST" action="{{ route('events.destroy', $event) }}" onsubmit="return confirm('Excluir evento permanentemente?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-3 bg-white/5 text-red-500 hover:bg-red-600 hover:text-white rounded-xl transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-20 text-center text-slate-600 font-black uppercase tracking-[0.3em] italic">Você ainda não possui eventos cadastrados</td>
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