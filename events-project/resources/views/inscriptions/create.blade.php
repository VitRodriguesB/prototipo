<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Inscrever-se em: <span class="text-indigo-600 dark:text-indigo-400">{{ $event->title }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-semibold">Passo 2 de 2: Selecione sua modalidade</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Escolha o tipo de inscrição que deseja para este evento.
                    </p>

                    <form method="POST" action="{{ route('inscriptions.store', $event->id) }}" class="mt-6">
                        @csrf

                        <!-- Lista de Tipos de Inscrição (Modalidades) -->
                        <div>
                            <x-input-label for="inscription_type_id" :value="__('Modalidade de Inscrição')" />
                            
                            @if ($inscriptionTypes->isEmpty())
                                <p class="mt-2 text-red-600 dark:text-red-400">O organizador ainda não cadastrou nenhuma modalidade de inscrição para este evento.</p>
                            @else
                                <select id="inscription_type_id" name="inscription_type_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="" disabled selected>{{ __('Selecione uma opção') }}</option>
                                    
                                    @foreach ($inscriptionTypes as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->type }}
                                            @if($type->allow_work_submission)
                                                (Permite submissão de trabalho)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            <x-input-error :messages="$errors->get('inscription_type_id')" class="mt-2" />
                        </div>

                        <div class="mt-8 border-t border-slate-100 pt-6">
                            <x-input-label :value="__('Escolha suas Atividades (Opcional)')" class="text-lg font-bold" />
                            <p class="text-sm text-slate-500 mb-4">Selecione as atividades que deseja participar. Atividades sem limite de vagas já estão inclusas na sua inscrição.</p>

                            <div class="space-y-4">
                                @php 
                                    $automaticActivities = $event->activities->where('max_participants', null);
                                    $optionalActivities = $event->activities->where('max_participants', '!=', null);
                                @endphp

                                @if($automaticActivities->count() > 0)
                                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Atividades Inclusas (Inscrição Automática)</h4>
                                        <ul class="list-disc list-inside text-sm text-slate-600 space-y-1">
                                            @foreach($automaticActivities as $act)
                                                <li>{{ $act->title }} ({{ \Carbon\Carbon::parse($act->start_time)->format('d/m H:i') }})</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if($optionalActivities->count() > 0)
                                    <div class="space-y-3">
                                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Atividades com Vagas Limitadas</h4>
                                        @foreach($optionalActivities as $act)
                                            @php 
                                                $remaining = $act->max_participants - $act->participants()->count();
                                                $isFull = $remaining <= 0;
                                            @endphp
                                            <div class="flex items-center p-4 border rounded-xl {{ $isFull ? 'bg-red-50 border-red-100 opacity-60' : 'bg-white border-slate-200' }}">
                                                <input type="checkbox" name="activities[]" value="{{ $act->id }}" 
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                       {{ $isFull ? 'disabled' : '' }}>
                                                <div class="ms-4 flex-1">
                                                    <div class="flex justify-between items-center">
                                                        <span class="font-bold text-slate-800">{{ $act->title }}</span>
                                                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full {{ $isFull ? 'bg-red-200 text-red-800' : 'bg-green-100 text-green-700' }}">
                                                            {{ $isFull ? 'Esgotado' : $remaining . ' vagas restantes' }}
                                                        </span>
                                                    </div>
                                                    <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($act->start_time)->format('d/m H:i') }} - {{ $act->location }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if($event->activities->count() == 0)
                                    <p class="text-sm text-slate-400 italic">Nenhuma atividade cadastrada para este evento ainda.</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('events.public.show', $event->id) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Voltar') }}
                            </a>

                            <!-- 👇 ESTA É A LINHA CORRIGIDA 👇 -->
                            <x-primary-button class="ms-4" :disabled="$inscriptionTypes->isEmpty()">
                                {{ __('Confirmar Inscrição') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>