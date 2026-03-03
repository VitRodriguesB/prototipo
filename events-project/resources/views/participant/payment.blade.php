<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pagamento da Inscrição') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-2xl font-bold mb-6">Pagar Evento: {{ $inscription->event->title }}</h3>
                    
                    <!-- Passo 1: Informações de Pagamento (PIX) -->
                    <div class="mb-8 border-b pb-6 border-gray-200 dark:border-gray-700">
                        <h4 class="text-lg font-semibold mb-3 text-indigo-600 dark:text-indigo-400">
                            Método: PIX - Copia e Cola
                        </h4>
                        
                        <p class="text-xl font-bold mb-2">Valor a Pagar: R$ {{ number_format($inscription->inscriptionType->price, 2, ',', '.') }}</p>
                        
                        @if ($inscription->event->pix_key)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Copie a chave abaixo para realizar o pagamento via PIX.
                            </p>
                            <div class="mt-3 p-3 bg-gray-100 dark:bg-gray-700 rounded-md flex justify-between items-center break-all">
                                <span class="font-mono text-base text-gray-800 dark:text-gray-100">{{ $inscription->event->pix_key }}</span>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $inscription->event->pix_key }}').then(() => alert('Chave PIX copiada!'));" class="ml-4 px-3 py-1 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-medium rounded hover:bg-gray-400 dark:hover:bg-gray-500">
                                    Copiar
                                </button>
                            </div>
                        @else
                            <p class="mt-3 text-red-500">O organizador não informou a Chave PIX. Por favor, entre em contato.</p>
                        @endif
                    </div>
                    
                    <!-- Passo 2: Envio do Comprovativo -->
                    <h4 class="text-lg font-semibold mb-3">2. Enviar Comprovante</h4>
                    
                    <form method="POST" action="{{ route('payment.store', $inscription->id) }}" enctype="multipart/form-data" id="uploadForm">
                        @csrf

                        <!-- Campo do Arquivo -->
                        <div>
                            <x-input-label for="proof" :value="__('Comprovante de Pagamento (Imagem ou PDF)')" />
                            <input id="proof" name="proof" type="file" required 
                                accept=".jpg,.jpeg,.png,.pdf"
                                class="block w-full text-sm text-gray-500
                                    file:me-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-600 file:text-white
                                    file:hover:bg-indigo-700 dark:file:bg-indigo-700 dark:file:hover:bg-indigo-600
                                    mt-1"
                                onchange="previewFile(this)"
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tipos permitidos: JPG, PNG, PDF (Máx. 2MB)</p>
                            <x-input-error :messages="$errors->get('proof')" class="mt-2" />
                        </div>

                        <!-- RF_F2: Preview do arquivo -->
                        <div id="previewContainer" class="mt-4 hidden">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prévia do arquivo:</p>
                            <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                                <!-- Preview de imagem -->
                                <img id="imagePreview" src="" alt="Prévia" class="hidden max-h-64 rounded-lg mx-auto">
                                <!-- Preview de PDF (ícone) -->
                                <div id="pdfPreview" class="hidden flex items-center justify-center">
                                    <svg class="w-16 h-16 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="ml-2 text-lg font-medium text-gray-700 dark:text-gray-300">Documento PDF</span>
                                </div>
                                <!-- Info do arquivo -->
                                <p id="fileInfo" class="text-center text-sm text-gray-500 dark:text-gray-400 mt-2"></p>
                            </div>
                        </div>

                        <!-- RF_F2: Barra de progresso -->
                        <div id="progressContainer" class="mt-4 hidden">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-indigo-700 dark:text-indigo-400">Enviando...</span>
                                <span id="progressPercent" class="text-sm font-medium text-indigo-700 dark:text-indigo-400">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div id="progressBar" class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Voltar ao Painel') }}
                            </a>

                            <x-primary-button class="ms-4" id="submitBtn">
                                {{ __('Enviar Comprovante') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- RF_F2: JavaScript para preview e progresso -->
    <script>
        function previewFile(input) {
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const pdfPreview = document.getElementById('pdfPreview');
            const fileInfo = document.getElementById('fileInfo');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                
                // Mostra o container
                previewContainer.classList.remove('hidden');
                
                // Info do arquivo
                fileInfo.textContent = `${file.name} (${fileSize} MB)`;

                if (file.type.startsWith('image/')) {
                    // Preview de imagem
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        pdfPreview.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    // Preview de PDF (ícone)
                    imagePreview.classList.add('hidden');
                    pdfPreview.classList.remove('hidden');
                }
            }
        }

        // Barra de progresso com XMLHttpRequest
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const submitBtn = document.getElementById('submitBtn');

            // Mostra a barra e desabilita o botão
            progressContainer.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enviando...';

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                    progressPercent.textContent = percent + '%';
                }
            });

            xhr.addEventListener('load', function() {
                if (xhr.status === 200 || xhr.status === 302) {
                    // Sucesso - redireciona
                    window.location.href = '{{ route("dashboard") }}';
                } else {
                    // Erro
                    alert('Erro ao enviar o comprovante. Tente novamente.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Enviar Comprovante';
                    progressContainer.classList.add('hidden');
                }
            });

            xhr.addEventListener('error', function() {
                alert('Erro de conexão. Verifique sua internet e tente novamente.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Enviar Comprovante';
                progressContainer.classList.add('hidden');
            });

            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        });
    </script>
</x-app-layout>