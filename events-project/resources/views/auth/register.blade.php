<x-guest-layout>
    <div class="mb-10 text-center select-none">
        <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Crie sua <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#4f46e5] to-[#9333ea]">Conta</span></h1>
        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-2">Escolha seu perfil e junte-se a nós</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Nome -->
        <div>
            <x-input-label for="name" :value="__('Nome Completo')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Seu nome" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs font-bold text-red-500" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('E-mail')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-red-500" />
        </div>

        <!-- Perfil -->
        <div>
            <x-input-label for="user_type_id" :value="__('Tipo de Perfil')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
            <select id="user_type_id" name="user_type_id" class="block w-full bg-[#1a1a1d] border-white/10 focus:border-[#4f46e5] focus:ring-[#4f46e5] rounded-full text-[#f0eee9] py-4 px-8 appearance-none cursor-pointer font-bold text-sm transition-all" required>
                <option value="" disabled selected>O que você será?</option>
                <option value="1" {{ old('user_type_id') == 1 ? 'selected' : '' }}>Participante</option>
                <option value="2" {{ old('user_type_id') == 2 ? 'selected' : '' }}>Organizador</option>
                <option value="3" {{ old('user_type_id') == 3 ? 'selected' : '' }}>Avaliador</option>
            </select>
            <x-input-error :messages="$errors->get('user_type_id')" class="mt-2 text-xs font-bold text-red-500" />
        </div>

        <!-- Senha -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="password" :value="__('Senha')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            </div>
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-4" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            </div>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold text-red-500" />

        <div class="pt-4">
            <x-primary-button class="w-full justify-center py-5">
                {{ __('Finalizar Cadastro') }}
            </x-primary-button>
        </div>

        <div class="text-center pt-8 border-t border-white/5 mt-8">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                Já possui uma conta? 
                <a href="{{ route('login') }}" class="text-white hover:text-[#4f46e5] transition-colors ml-1 border-b border-white/20 hover:border-[#4f46e5]">
                    Acesse agora
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>