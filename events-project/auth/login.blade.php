<x-guest-layout>
    <!-- Cabeçalho do Card -->
    <div class="mb-10 text-center select-none">
        <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Acesse sua <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#4f46e5] to-[#9333ea]">Conta</span></h1>
        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-2">Bem-vindo ao portal de eventos pro</p>
    </div>

    <!-- Mensagens de Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Endereço de E-mail')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ms-1" />
            <x-text-input id="email" class="block w-full bg-[#1a1a1d] border-white/5 focus:border-[#4f46e5] focus:ring-[#4f46e5] rounded-2xl text-[#f0eee9] placeholder-slate-600 py-4 px-6" 
                            type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                            placeholder="exemplo@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold" />
        </div>

        <!-- Senha -->
        <div class="relative">
            <div class="flex justify-between items-center mb-2 ms-1">
                <x-input-label for="password" :value="__('Sua Senha')" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-black text-indigo-400 hover:text-indigo-300 uppercase tracking-widest transition-colors" href="{{ route('password.request') }}">
                        {{ __('Esqueceu?') }}
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full bg-[#1a1a1d] border-white/5 focus:border-[#4f46e5] focus:ring-[#4f46e5] rounded-2xl text-[#f0eee9] placeholder-slate-600 py-4 px-6"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold" />
        </div>

        <!-- Lembrar de mim -->
        <div class="block ms-1">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded-lg bg-[#1a1a1d] border-white/10 text-[#4f46e5] shadow-sm focus:ring-[#4f46e5] transition-all" name="remember">
                <span class="ms-2 text-xs font-bold text-slate-500 group-hover:text-slate-300 transition-colors">{{ __('Manter conectado') }}</span>
            </label>
        </div>

        <!-- Botão Entrar -->
        <div class="pt-4">
            <x-primary-button class="w-full justify-center py-5 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white font-black rounded-2xl shadow-[0_15px_30px_rgba(79,70,229,0.2)] hover:shadow-[0_20px_40px_rgba(79,70,229,0.4)] hover:scale-[1.02] transition-all uppercase tracking-[0.2em] text-xs">
                {{ __('Entrar no Sistema') }}
            </x-primary-button>
        </div>

        <!-- Link para Registro -->
        <div class="text-center pt-8 border-t border-white/5 mt-8">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                Ainda não tem acesso? 
                <a href="{{ route('register') }}" class="text-white hover:text-[#4f46e5] transition-colors ml-1 border-b border-white/20 hover:border-[#4f46e5]">
                    Crie sua conta agora
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>