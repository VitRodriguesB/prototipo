<nav x-data="{ mobileMenu: false }" class="bg-[#050505]/80 backdrop-blur-xl border-b border-white/5 sticky top-0 z-[100]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto object-contain drop-shadow-[0_0_8px_rgba(79,70,229,0.4)]" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::check() && Auth::user()->user_type_id == 4)
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="text-[10px] font-black uppercase tracking-widest text-amber-400 border-amber-500/50">
                            {{ __('Gestão de Usuários') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::check() && Auth::user()->user_type_id == 2)
                        <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')" class="text-[10px] font-black uppercase tracking-widest">
                            {{ __('Gestão de Eventos') }}
                        </x-nav-link>
                        <x-nav-link :href="route('organization.payments.index')" :active="request()->routeIs('organization.payments.index')" class="text-[10px] font-black uppercase tracking-widest">
                            {{ __('Validar Pix') }}
                        </x-nav-link>
                        <x-nav-link :href="route('submissions.index')" :active="request()->routeIs('submissions.index')" class="text-[10px] font-black uppercase tracking-widest">
                            {{ __('Grade Científica') }}
                        </x-nav-link>
                        <x-nav-link :href="route('attendance.scanner')" :active="request()->routeIs('attendance.scanner')" class="text-[10px] font-black uppercase tracking-widest text-indigo-400">
                            {{ __('Check-in Mobile') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent focus:border-transparent text-xs font-black uppercase tracking-widest rounded-xl text-slate-400 bg-white/5 hover:text-white hover:bg-white/10 transition-all focus:outline-none focus:ring-0 ring-0 shadow-lg">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden shadow-2xl">
                            <x-dropdown-link :href="route('profile.edit')" class="text-slate-400 hover:bg-white/5 hover:text-white font-bold text-xs uppercase tracking-widest py-3">
                                {{ __('Meu Perfil') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();"
                                        class="text-red-400 hover:bg-red-500/10 font-bold text-xs uppercase tracking-widest py-3 border-t border-white/5">
                                    {{ __('Sair do Sistema') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="mobileMenu = ! mobileMenu" class="inline-flex items-center justify-center p-3 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenu, 'inline-flex': ! mobileMenu }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! mobileMenu, 'inline-flex': mobileMenu }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': mobileMenu, 'hidden': ! mobileMenu}" class="hidden sm:hidden bg-[#050505] border-t border-white/5 shadow-2xl">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @if(Auth::check() && Auth::user()->user_type_id == 4)
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="rounded-2xl font-black uppercase text-[10px] tracking-widest py-4 text-amber-400 bg-amber-500/5 border border-amber-500/20">
                    {{ __('Gestão de Usuários') }}
                </x-responsive-nav-link>
            @endif
            
            @if(Auth::check() && Auth::user()->user_type_id == 2)
                <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')" class="rounded-2xl font-black uppercase text-[10px] tracking-widest py-4">
                    {{ __('Gestão de Eventos') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('organization.payments.index')" :active="request()->routeIs('organization.payments.index')" class="rounded-2xl font-black uppercase text-[10px] tracking-widest py-4">
                    {{ __('Validar Pix') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('attendance.scanner')" :active="request()->routeIs('attendance.scanner')" class="rounded-2xl font-black uppercase text-[10px] tracking-widest py-4 text-indigo-400">
                    {{ __('Check-in Mobile') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/5 bg-white/5 px-4">
            <div class="px-4 py-4">
                <div class="font-black text-sm text-white uppercase tracking-tighter italic">{{ Auth::user()->name }}</div>
                <div class="font-bold text-[10px] text-slate-500 uppercase tracking-widest">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 pb-4">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-2xl font-black uppercase text-[10px] tracking-widest py-4">
                    {{ __('Meu Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="rounded-2xl font-black uppercase text-[10px] tracking-widest py-4 text-red-400 bg-red-500/5">
                        {{ __('Sair do Sistema') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>