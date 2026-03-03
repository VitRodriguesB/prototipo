<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Painel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- 
              👇 MUDANÇA DE ESTILO "PREMIUM" 👇
              shadow-sm sm:rounded-lg -> shadow-xl sm:rounded-2xl
            -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    {{ __("You're logged in!") }}
                    
                    @if(Auth::user()->user_type_id == 1)
                        <p>Você está logado como <strong>Participante</strong>.</p>
                    @elseif(Auth::user()->user_type_id == 2)
                        <p>Você está logado como <strong>Organizador</strong>.</p>
                    @elseif(Auth::user()->user_type_id == 3)
                        <p>Você está logado como <strong>Avaliador</strong>.</p>
                    @endif
                </div>
            </div>

            {{-- RF_B1: Mensagem de confirmação de e-mail bem-sucedida --}}
            @if(request()->has('verified') && request('verified') == 1)
                <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <strong class="font-bold">Cadastro confirmado com sucesso!</strong>
                    </div>
                    <p class="mt-1 text-sm">Seu e-mail foi verificado. Agora você tem acesso completo ao sistema.</p>
                </div>
            @endif

            <!-- ===== PAINEL DO PARTICIPANTE (ID 1) ===== -->
            @if(Auth::user()->user_type_id == 1)
                <!-- 
                  👇 MUDANÇA DE ESTILO "PREMIUM" 👇
                  shadow-sm sm:rounded-lg -> shadow-xl sm:rounded-2xl
                -->
                <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                    <div class="p-6 text-slate-900">
                        <h3 class="text-lg font-semibold mb-4">Minhas Inscrições</h3>

                        @forelse($userInscriptions ?? [] as $inscription)
                            <div class="card mb-4 p-4 border rounded-lg border-slate-200">
                                <div class="card-body">
                                    <h5 class="card-title font-bold text-lg text-slate-800">{{ $inscription->event->title }}</h5>
                                    
                                    <!-- Área de Status/Ação -->
                                    <div class="card-text mt-2">
                                        <span class="font-medium">Status:</span> 
                                        
                                        @if($inscription->status == 1)
                                            <!-- 1. Confirmada -->
                                            <span class="font-medium text-green-600">Confirmada</span>

                                        @elseif($inscription->payment && $inscription->payment->status == 1)
                                            <!-- 2. Em Análise -->
                                            <span class="font-medium text-orange-600">Pagamento em Análise</span>
                                            <small class="block text-slate-500 mt-1">Seu comprovante foi enviado e está aguardando aprovação.</small>

                                        @elseif($inscription->payment && $inscription->payment->status == 3)
                                            <!-- 3. Recusado -->
                                            <span class="font-medium text-red-600">Pagamento Recusado</span>
                                            <small class="block text-slate-500 mt-1">Motivo: {{ $inscription->payment->rejection_reason ?? 'Não especificado' }}</small>
                                            
                                            <a href="{{ route('payment.create', $inscription) }}" class="inline-block mt-3 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700">
                                                Enviar Novo Comprovante
                                            </a>

                                        @else
                                            <!-- 4. Aguardando Pagamento -->
                                            <span class="font-medium text-blue-600">Aguardando Pagamento</span>
                                            <a href="{{ route('payment.create', $inscription) }}" class="inline-block mt-3 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700">
                                                Realizar Pagamento
                                            </a>
                                        @endif
                                    </div>
                                    
                                    <!-- Área de Submissão de Trabalho (se houver) -->
                                    <div class="border-t border-slate-200 mt-4 pt-4">
                                        @if($inscription->work_id)
                                            <!-- 3c. Já submeteu -->
                                            <p class="font-medium text-green-600">Trabalho submetido com sucesso!</p>
                                            <small class="block text-slate-500">(Título: {{ $inscription->work->title }})</small>
                                        
                                        @elseif($inscription->status == 1 && $inscription->inscriptionType->allow_work_submission)
                                            <!-- 3a. Pode submeter -->
                                            <a href="{{ route('works.create', $inscription->event) }}" class="inline-block px-4 py-2 text-sm font-medium text-white bg-slate-700 rounded-md shadow-sm hover:bg-slate-800">
                                                Submeter Trabalho
                                            </a>
                                        @elseif($inscription->status != 1 && $inscription->inscriptionType->allow_work_submission)
                                            <!-- 3b. Pagamento pendente -->
                                            <p class="text-sm text-slate-500">A submissão de trabalho será liberada após a confirmação do pagamento.</p>
                                        @endif
                                    </div>

                                    {{-- RF_F10: QR Code de Presença (apenas para inscrições confirmadas) --}}
                                    @if($inscription->status == 1 && !$inscription->attended)
                                        <div class="border-t border-slate-200 mt-4 pt-4">
                                            <h4 class="text-sm font-semibold text-slate-700 mb-2">QR Code de Presença</h4>
                                            <div class="flex items-center gap-4">
                                                <img src="{{ route('attendance.qrcode', $inscription) }}" 
                                                     alt="QR Code de Presença" 
                                                     class="w-32 h-32 border border-slate-200 rounded-lg">
                                                <div class="text-sm text-slate-600">
                                                    <p>Apresente este QR Code no dia do evento para confirmar sua presença.</p>
                                                    <p class="mt-1 text-xs text-slate-400">Válido até: {{ \Carbon\Carbon::parse($inscription->event->event_date)->format('d/m/Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($inscription->attended)
                                        <div class="border-t border-slate-200 mt-4 pt-4">
                                            <div class="flex items-center text-green-600">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="font-medium">Presença Confirmada!</span>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- RF_S7: Área de Certificados --}}
                                    <div class="border-t border-slate-200 mt-4 pt-4">
                                        <h4 class="text-sm font-semibold text-slate-700 mb-2">Certificados</h4>
                                        
                                        <div class="flex flex-wrap gap-2">
                                            {{-- Certificado de Participação --}}
                                            @if($inscription->attended)
                                                <a href="{{ route('certificates.participation', $inscription) }}" 
                                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 rounded-md shadow-sm hover:from-purple-700 hover:to-indigo-700">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Certificado de Participação
                                                </a>
                                            @else
                                                <span class="inline-flex items-center px-3 py-2 text-sm text-slate-500 bg-slate-100 rounded-md">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    Participação (Aguardando confirmação)
                                                </span>
                                            @endif

                                            {{-- Certificado de Apresentação (se submeteu trabalho) --}}
                                            @if($inscription->work_id)
                                                @if($inscription->presented_work)
                                                    <a href="{{ route('certificates.presentation', $inscription->work) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-teal-600 rounded-md shadow-sm hover:from-green-700 hover:to-teal-700">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                                        </svg>
                                                        Certificado de Apresentação
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-2 text-sm text-slate-500 bg-slate-100 rounded-md">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Apresentação (Aguardando confirmação)
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        
                        @empty
                            <div class="alert alert-info text-slate-600">
                                Você ainda não possui inscrições.
                            </div>
                        @endforelse

                    </div>
                </div>
            @endif
            <!-- ===== FIM DO PAINEL DO PARTICIPANTE ===== -->
            
            
            <!-- ===== PAINEL DO AVALIADOR (ID 3) ===== -->
            @if(Auth::user()->user_type_id == 3)
                <!-- 
                  👇 MUDANÇA DE ESTILO "PREMIUM" 👇
                  shadow-sm sm:rounded-lg -> shadow-xl sm:rounded-2xl
                -->
                <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                    <div class="p-6 text-slate-900">
                        <h3 class="text-lg font-semibold mb-4">Trabalhos Pendentes para Avaliação</h3>

                        @forelse ($pendingReviews ?? [] as $review)
                            <div class="mb-4 p-4 border rounded-lg border-slate-200">
                                <h4 class="font-bold text-lg text-slate-800">{{ $review->work->title }}</h4>
                                <p class="text-sm text-slate-500">Autor: {{ $review->work->user->name }}</p>
                                <p class="text-sm text-slate-500">Submetido em: {{ $review->work->created_at->format('d/m/Y') }}</p>
                                
                                <div class="mt-4">
                                    <a href="{{ route('reviews.edit', $review) }}" class="inline-block px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700">
                                        Avaliar Trabalho
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-600">Você não possui trabalhos pendentes para avaliação no momento.</p>
                        @endforelse
                    </div>
                </div>
            @endif
            <!-- ===== FIM DO PAINEL DO AVALIADOR ===== -->

        </div>
    </div>
</x-app-layout>