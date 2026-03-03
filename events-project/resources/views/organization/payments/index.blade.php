<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Validação de Pagamentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-100 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <h3 class="text-lg font-semibold mb-4">{{ __('Pagamentos Pendentes de Análise') }}</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Participante
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Evento / Modalidade
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Valor / Data Envio
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Comprovante
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($pendingPayments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $payment->inscription->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <span class="font-semibold">{{ $payment->inscription->event->title }}</span>
                                            <br><span class="text-xs text-gray-500 dark:text-gray-400">Tipo: {{ $payment->inscription->inscriptionType->type }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            R$ {{ number_format($payment->amount, 2, ',', '.') }}
                                            <br><span class="text-xs text-gray-500 dark:text-gray-400">Enviado: {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <!-- Link para visualizar o comprovante -->
                                            <a href="{{ Storage::url($payment->proof_path) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-100 underline">
                                                Ver
                                            </a>
                                            <span class="mx-1 text-gray-400">|</span>
                                            <!-- RF_F2: Botão de Download -->
                                            <a href="{{ route('organization.payments.download', $payment->id) }}" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-100 underline">
                                                Baixar
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            
                                            <!-- Botão Aprovar (POST) -->
                                            <form method="POST" action="{{ route('organization.payments.approve', $payment->inscription->id) }}" class="inline-block" onsubmit="return confirm('Confirmar APROVAÇÃO do pagamento de {{ $payment->inscription->user->name }}?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-md">
                                                    Aprovar
                                                </button>
                                            </form>
                                            
                                            <!-- Botão Recusar (Precisa de um modal para o motivo, mas faremos simples por agora) -->
                                            <form method="POST" action="{{ route('organization.payments.reject', $payment->inscription->id) }}" class="inline-block" onsubmit="
                                                const reason = prompt('Qual o motivo da recusa? (Mínimo 10 caracteres)');
                                                if (reason && reason.length >= 10) {
                                                    this.querySelector('input[name=rejection_reason]').value = reason;
                                                    return true;
                                                }
                                                return false;
                                            ">
                                                @csrf
                                                <!-- Campo escondido para enviar o motivo -->
                                                <input type="hidden" name="rejection_reason" value=""> 
                                                <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md">
                                                    Recusar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            Nenhum pagamento pendente de análise no momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>