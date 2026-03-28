<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#121214]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10">
                <h2 class="text-3xl font-black text-white uppercase tracking-tight italic pe-4 pr-3">
                    MEU <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">PERFIL</span>
                </h2>
                <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-2">
                    Gerencie suas informações de conta, segurança e privacidade
                </p>
            </div>

            <div class="space-y-8">
                <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-8 sm:p-10 shadow-2xl">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-8 sm:p-10 shadow-2xl">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-8 sm:p-10 shadow-2xl border-red-500/10">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
