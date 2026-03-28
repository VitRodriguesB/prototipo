<section class="space-y-6">
    <header>
        <h2 class="text-lg font-black text-white uppercase italic mb-1">
            {{ __('Excluir Conta') }}
        </h2>

        <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-6 leading-relaxed">
            {{ __('Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente apagados.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-8 py-4 bg-red-500/10 text-red-500 border border-red-500/20 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all"
    >{{ __('Excluir Permanentemente') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10 bg-[#0a0a0a]">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-black text-white uppercase italic tracking-tighter">
                {{ __('Você tem certeza?') }}
            </h2>

            <p class="mt-4 text-[10px] text-slate-500 font-black uppercase tracking-widest leading-relaxed">
                {{ __('Esta ação não pode ser desfeita. Por favor, insira sua senha para confirmar a exclusão permanente da sua conta.') }}
            </p>

            <div class="mt-8">
                <label for="password" class="sr-only">{{ __('Senha') }}</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all shadow-inner"
                    placeholder="{{ __('Sua Senha') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
            </div>

            <div class="mt-10 flex justify-end gap-4 border-t border-white/5 pt-8">
                <button type="button" x-on:click="$dispatch('close')" class="px-8 py-4 bg-white/5 text-slate-500 border border-white/10 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:text-white transition-all">
                    {{ __('Cancelar') }}
                </button>

                <button type="submit" class="px-8 py-4 bg-red-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-red-500/20 hover:bg-red-700 transition-all">
                    {{ __('Excluir Agora') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
