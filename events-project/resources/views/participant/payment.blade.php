<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Pagamento da Inscrição') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-[2.5rem] border border-slate-100">
                <div class="p-6 sm:p-12 text-slate-900">

                    <div class="mb-10">
                        <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">Finalizar Inscrição</h3>
                        <p class="text-sm text-slate-500 mt-1 font-medium">Evento: <span class="text-indigo-600 font-bold">{{ $inscription->event->title }}</span></p>
                    </div>
                    
                    <!-- PASSO 1: PIX -->
                    <div class="mb-10 p-8 bg-slate-50 rounded-[2rem] border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <svg class="w-24 h-24 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z"></path></svg>
                        </div>

                        <h4 class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-4">Método: PIX - Copia e Cola</h4>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Total a Pagar</span>
                                <span class="text-4xl font-black text-slate-900 leading-none">R$ {{ number_format($inscription->inscriptionType->price, 2, ',', '.') }}</span>
                            </div>
                            
                            @if ($inscription->event->pix_key)
                                <div class="flex-1 max-w-md">
                                    <div class="p-4 bg-white border-2 border-indigo-100 rounded-2xl flex items-center justify-between shadow-sm">
                                        <span class="font-mono text-xs text-slate-600 break-all select-all">{{ $inscription->event->pix_key }}</span>
                                        <button type="button" onclick="navigator.clipboard.writeText('{{ $inscription->event->pix_key }}').then(() => alert('Chave PIX copiada!'));" class="ms-4 px-4 py-2 bg-indigo-600 text-white text-[10px] font-black rounded-xl hover:bg-indigo-700 transition-all uppercase tracking-widest">
                                            Copiar
                                        </button>
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-red-500 font-bold bg-red-50 p-4 rounded-2xl border border-red-100">O organizador não informou a Chave PIX. Por favor, entre em contato.</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- PASSO 2: UPLOAD -->
                    <div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">2. Enviar Comprovante de Pagamento</h4>
                        
                        <form method="POST" action="{{ route('payment.store', $inscription->id) }}" enctype="multipart/form-data" id="uploadForm" class="space-y-6">
                            @csrf

                            <div class="relative">
                                <label for="proof" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-slate-200 rounded-[2rem] cursor-pointer bg-slate-50 hover:bg-white hover:border-indigo-400 transition-all group">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="mb-2 text-sm text-slate-500"><span class="font-black">Clique para selecionar</span> ou arraste o arquivo</p>
                                        <p class="text-xs text-slate-400 uppercase font-bold tracking-tighter">PDF, JPG ou PNG (Máx. 2MB)</p>
                                    </div>
                                    <input id="proof" name="proof" type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" onchange="previewFile(this)" required />
                                </label>
                                <x-input-error :messages="$errors->get('proof')" class="mt-2" />
                            </div>

                            <!-- Preview -->
                            <div id="previewContainer" class="hidden animate-in fade-in zoom-in duration-300">
                                <div class="relative p-2 bg-white border-2 border-slate-100 rounded-3xl shadow-xl inline-block max-w-full">
                                    <img id="imagePreview" src="" alt="Prévia" class="hidden max-h-64 rounded-2xl mx-auto">
                                    <div id="pdfPreview" class="hidden flex items-center p-8 gap-4 bg-red-50 rounded-2xl">
                                        <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9v-2h2v2zm0-4H9V7h2v5z"></path></svg>
                                        <span class="text-sm font-black text-red-700 uppercase tracking-widest">Documento PDF Selecionado</span>
                                    </div>
                                    <p id="fileInfo" class="mt-2 text-center text-[10px] font-black text-slate-400 uppercase"></p>
                                </div>
                            </div>

                            <!-- Progresso -->
                            <div id="progressContainer" class="hidden space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Transmitindo arquivo...</span>
                                    <span id="progressPercent" class="text-xs font-black text-indigo-600">0%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden shadow-inner">
                                    <div id="progressBar" class="bg-indigo-600 h-full transition-all duration-300 shadow-lg shadow-indigo-200" style="width: 0%"></div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-10 border-t border-slate-100">
                                <a href="{{ route('dashboard') }}" class="text-xs font-black text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">
                                    Voltar ao Painel
                                </a>
                                <button type="submit" id="submitBtn" class="px-10 py-4 bg-slate-900 text-white font-black rounded-2xl shadow-2xl hover:bg-black transition-all active:scale-95 uppercase tracking-widest text-xs">
                                    Enviar Comprovante
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function previewFile(input) {
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const pdfPreview = document.getElementById('pdfPreview');
            const fileInfo = document.getElementById('fileInfo');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                previewContainer.classList.remove('hidden');
                fileInfo.textContent = `${file.name} - ${fileSize} MB`;

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        pdfPreview.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.classList.add('hidden');
                    pdfPreview.classList.remove('hidden');
                }
            }
        }

        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const submitBtn = document.getElementById('submitBtn');

            progressContainer.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.textContent = 'PROCESSANDO...';

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                    progressPercent.textContent = percent + '%';
                }
            });

            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    window.location.href = '{{ route("dashboard") }}';
                } else {
                    alert('Erro no envio. Verifique o tamanho do arquivo (Máx 2MB).');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Enviar Comprovante';
                    progressContainer.classList.add('hidden');
                }
            });

            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        });
    </script>
</x-app-layout>