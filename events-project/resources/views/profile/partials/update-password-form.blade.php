<section>
    <header>
        <h2 class="text-lg font-black text-white uppercase italic mb-1">
            {{ __('Atualizar Senha') }}
        </h2>

        <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-6">
            {{ __('Certifique-se de que sua conta está usando uma senha longa e aleatória para permanecer segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">{{ __('Senha Atual') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
        </div>

        <div>
            <label for="update_password_password" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">{{ __('Nova Senha') }}</label>
            <input id="update_password_password" name="password" type="password" class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">{{ __('Confirmar Nova Senha') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
        </div>

        <div class="flex items-center gap-6 pt-4 border-t border-white/5">
            <button type="submit" class="px-8 py-4 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:scale-[1.02] transition-all">
                {{ __('Salvar Nova Senha') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-black text-emerald-400 uppercase tracking-widest"
                >{{ __('Senha atualizada.') }}</p>
            @endif
        </div>
    </form>
</section>
