<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#121214]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Mensagens de Alerta --}}
            @if(session('success'))
                <div class="mb-8 p-4 bg-indigo-500/10 border border-indigo-500/50 text-indigo-400 rounded-2xl flex items-center shadow-lg shadow-indigo-500/5">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold uppercase tracking-tight text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="space-y-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <h3 class="text-3xl font-black text-white uppercase tracking-tight italic pe-4 pr-3">Administração de <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Usuários</span></h3>
                        <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-2">Gerenciamento de permissões e perfis de acesso</p>
                    </div>
                    <div class="px-6 py-2 bg-white/5 border border-white/10 rounded-2xl text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em]">
                        Total: {{ $users->total() }} Usuários
                    </div>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/5 bg-white/[0.02]">
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Usuário</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">E-mail</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Tipo Atual</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($users as $user)
                                    <tr class="hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 font-black text-sm border border-indigo-500/20">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <span class="text-white font-bold text-sm tracking-tight italic">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="text-slate-400 font-medium text-sm">{{ $user->email }}</span>
                                        </td>
                                        <td class="px-6 py-5">
                                            @php
                                                $badgeClass = match($user->user_type_id) {
                                                    1 => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
                                                    2 => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                                    3 => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                                    4 => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                                    default => 'bg-white/5 text-white/40 border-white/10'
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $badgeClass }}">
                                                {{ $user->userType->type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="flex items-center justify-end gap-2">
                                                @csrf
                                                <select name="new_role" class="bg-[#121214] border-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-2 pl-3 pr-8">
                                                    @foreach($userTypes as $type)
                                                        <option value="{{ $type->id }}" {{ $user->user_type_id == $type->id ? 'selected' : '' }}>
                                                            {{ $type->type }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="p-2.5 bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20" title="Atualizar Nível">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($users->hasPages())
                        <div class="px-6 py-6 border-t border-white/5 bg-white/[0.01]">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
