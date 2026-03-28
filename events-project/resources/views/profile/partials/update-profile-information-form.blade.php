<section>
    <header>
        <h2 class="text-lg font-black text-white uppercase italic mb-1">
            {{ __('Informações do Perfil') }}
        </h2>

        <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-6">
            {{ __("Atualize suas informações de perfil e endereço de e-mail.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">{{ __('Nome') }}</label>
            <input id="name" name="name" type="text" class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-[10px] font-bold text-red-500 uppercase" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">{{ __('E-mail') }}</label>
            <input id="email" name="email" type="email" class="w-full bg-[#121214] border border-white/10 rounded-xl text-white px-4 py-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-inner" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2 text-[10px] font-bold text-red-500 uppercase" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-xs mt-4 text-slate-400 font-bold uppercase tracking-widest">
                        {{ __('Seu endereço de e-mail não foi verificado.') }}

                        <button form="send-verification" class="text-indigo-400 hover:text-white transition-colors underline">
                            {{ __('Clique aqui para re-enviar o e-mail de verificação.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-black text-[10px] text-emerald-400 uppercase tracking-widest">
                            {{ __('Um novo link de verificação foi enviado para seu e-mail.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-6 pt-4 border-t border-white/5">
            <button type="submit" class="px-8 py-4 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:scale-[1.02] transition-all">
                {{ __('Salvar Informações') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-black text-emerald-400 uppercase tracking-widest"
                >{{ __('Salvo com sucesso.') }}</p>
            @endif
        </div>
    </form>
</section>
