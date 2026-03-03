<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Controle de Presença - QR Code') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensagens de Feedback --}}
            @if (session('success'))
                <div id="successAlert" class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div id="errorAlert" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Escanear QR Code do Participante
                    </h3>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Aponte a câmera para o QR Code do participante. A presença será confirmada automaticamente.
                    </p>

                    {{-- Área do Scanner --}}
                    <div id="reader" class="mx-auto" style="max-width: 500px; border-radius: 12px; overflow: hidden;"></div>

                    {{-- Resultado --}}
                    <div id="scanResult" class="hidden mt-6 p-4 rounded-lg text-center">
                        <div id="resultIcon" class="mx-auto mb-3"></div>
                        <p id="resultMessage" class="text-lg font-semibold"></p>
                        <p id="resultDetails" class="text-sm mt-1"></p>
                    </div>

                    {{-- Contador de Presenças --}}
                    <div class="mt-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Presenças Confirmadas Nesta Sessão</p>
                        <p id="attendanceCount" class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Biblioteca html5-qrcode --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    
    <script>
        let attendanceCount = 0;
        let isProcessing = false;

        function showResult(success, message, details = '') {
            const scanResult = document.getElementById('scanResult');
            const resultIcon = document.getElementById('resultIcon');
            const resultMessage = document.getElementById('resultMessage');
            const resultDetails = document.getElementById('resultDetails');

            scanResult.classList.remove('hidden', 'bg-green-100', 'bg-red-100', 'bg-yellow-100');
            
            if (success) {
                scanResult.classList.add('bg-green-100');
                resultIcon.innerHTML = '<svg class="w-16 h-16 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
                attendanceCount++;
                document.getElementById('attendanceCount').textContent = attendanceCount;
            } else {
                scanResult.classList.add('bg-red-100');
                resultIcon.innerHTML = '<svg class="w-16 h-16 text-red-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
            }

            resultMessage.textContent = message;
            resultDetails.textContent = details;

            // Esconde após 5 segundos
            setTimeout(() => {
                scanResult.classList.add('hidden');
            }, 5000);
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;

            // Verifica se é uma URL do nosso sistema
            if (!decodedText.includes('/presenca/')) {
                showResult(false, 'QR Code inválido', 'Este QR Code não é do sistema de eventos.');
                setTimeout(() => { isProcessing = false; }, 2000);
                return;
            }

            // Faz a requisição AJAX
            fetch(decodedText, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showResult(true, data.message, `Participante: ${data.participant}`);
                } else {
                    showResult(false, data.message, data.participant ? `Participante: ${data.participant}` : '');
                }
            })
            .catch(error => {
                showResult(false, 'Erro ao validar QR Code', 'Verifique sua conexão.');
            })
            .finally(() => {
                setTimeout(() => { isProcessing = false; }, 2000);
            });
        }

        function onScanFailure(error) {
            // Silenciosamente ignora erros de scan (frames sem QR)
        }

        // Inicializa o scanner
        const html5QrcodeScanner = new Html5Qrcode("reader");
        
        html5QrcodeScanner.start(
            { facingMode: "environment" }, // Câmera traseira
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            document.getElementById('reader').innerHTML = `
                <div class="p-6 text-center bg-yellow-100 rounded-lg">
                    <p class="text-yellow-800 font-medium">Não foi possível acessar a câmera.</p>
                    <p class="text-sm text-yellow-700 mt-2">Verifique se você concedeu permissão para usar a câmera.</p>
                </div>
            `;
        });
    </script>
</x-app-layout>
