<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#121214] min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10">
                <h2 class="text-3xl font-black text-white uppercase tracking-tight italic pr-3">
                    PAGAMENTO DA <span class="inline-block pr-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">INSCRIÇÃO</span>
                </h2>
                <p class="text-xs text-slate-500 mt-2 font-bold uppercase tracking-widest italic">Evento: {{ $inscription->event->title }}</p>
            </div>

            <div class="bg-[#0a0a0a] border border-white/5 rounded-2xl p-8 sm:p-10 shadow-2xl">
                
                <!-- PASSO 1: PIX -->
                <div class="mb-12">
                    <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-6 border-b border-white/5 pb-2">1. Pagamento via Pix (Copia e Cola)</h4>
                    
                    <div class="bg-[#121214] border border-indigo-500/30 rounded-xl p-6 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden shadow-inner">
                        <div class="absolute -right-4 -bottom-4 opacity-5 pointer-events-none">
                            <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z"></path></svg>
                        </div>

                        <div class="text-center md:text-left relative z-10">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block mb-1">Valor Total</span>
                            <span class="text-4xl font-black text-white italic tracking-tighter leading-none">R$ {{ number_format($inscription->inscriptionType->price, 2, ',', '.') }}</span>
                        </div>
                        
                        @if ($inscription->event->pix_key)
                            <div class="flex-1 w-full max-w-md relative z-10">
                                <div class="bg-black/50 border border-white/5 rounded-xl p-2 flex flex-col sm:flex-row items-center gap-3">
                                    <div class="bg-black text-slate-400 px-4 py-3 rounded-lg w-full font-mono text-xs break-all select-all border border-white/5">
                                        {{ $inscription->event->pix_key }}
                                    </div>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $inscription->event->pix_key }}').then(() => alert('Chave PIX copiada!'));" 
                                            class="w-full sm:w-auto px-6 py-3 bg-white/5 text-white text-[9px] font-black rounded-lg hover:bg-white hover:text-black transition-all uppercase tracking-widest border border-white/10 whitespace-nowrap">
                                        Copiar
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                                <p class="text-[10px] text-red-400 font-black uppercase tracking-widest">O organizador não informou a Chave PIX.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- PASSO 2: UPLOAD -->
                <div>
                    <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6 border-b border-white/5 pb-2">2. Enviar Comprovante de Pagamento</h4>
                    
                    <form method="POST" action="{{ route('payment.store', $inscription->id) }}" enctype="multipart/form-data" id="uploadForm" class="space-y-8">
                        @csrf

                        <div class="relative group">
                            <label for="proof" class="flex flex-col items-center justify-center w-full h-56 border-2 border-dashed border-white/10 rounded-2xl cursor-pointer bg-[#121214] hover:bg-black hover:border-indigo-500/50 transition-all shadow-inner relative overflow-hidden">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-10 text-center px-4">
                                    <div class="w-16 h-16 bg-indigo-500/10 text-indigo-400 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    </div>
                                    <p class="mb-2 text-sm text-slate-300 font-bold uppercase tracking-tight">Arraste ou <span class="text-indigo-400 underline underline-offset-4">Clique para selecionar</span></p>
                                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest">PDF, JPG ou PNG (Máx. 2MB)</p>
                                </div>
                                <input id="proof" name="proof" type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" onchange="previewFile(this)" required />
                            </label>
                            <x-input-error :messages="$errors->get('proof')" class="mt-2 text-[10px] font-bold text-red-500 uppercase" />
                        </div>

                        <!-- Preview de Arquivo -->
                        <div id="previewContainer" class="hidden animate-in fade-in zoom-in duration-300">
                            <div class="relative p-4 bg-[#121214] border border-white/10 rounded-2xl shadow-2xl inline-block max-w-full overflow-hidden">
                                <img id="imagePreview" src="" alt="Prévia" class="hidden max-h-64 rounded-xl mx-auto shadow-2xl">
                                <div id="pdfPreview" class="hidden flex items-center p-8 gap-6 bg-indigo-500/10 rounded-xl border border-indigo-500/20">
                                    <svg class="w-12 h-12 text-indigo-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9v-2h2v2zm0-4H9V7h2v5z"></path></svg>
                                    <span class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">Documento PDF Carregado</span>
                                </div>
                                <p id="fileInfo" class="mt-4 text-center text-[10px] font-black text-slate-500 uppercase tracking-widest truncate max-w-xs mx-auto"></p>
                            </div>
                        </div>

                        <!-- Barra de Progresso Customizada -->
                        <div id="progressContainer" class="hidden space-y-3">
                            <div class="flex justify-between items-center px-2">
                                <span class="text-[9px] font-black text-indigo-400 uppercase tracking-[0.3em] animate-pulse">Transferindo dados para o servidor...</span>
                                <span id="progressPercent" class="text-xs font-black text-white italic">0%</span>
                            </div>
                            <div class="w-full bg-[#121214] border border-white/5 rounded-full h-3 overflow-hidden shadow-inner">
                                <div id="progressBar" class="bg-gradient-to-r from-[#4f46e5] to-[#9333ea] h-full transition-all duration-300 shadow-[0_0_15px_rgba(79,70,229,0.5)]" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-10 border-t border-white/5">
                            <a href="{{ route('dashboard') }}" class="text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-[0.3em] transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                Voltar ao Painel
                            </a>
                            <button type="submit" id="submitBtn" class="w-full md:w-auto px-12 py-5 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-indigo-500/20 hover:scale-[1.02] active:scale-95 transition-all">
                                Enviar Comprovante Agora
                            </button>
                        </div>
                    </form>
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
                fileInfo.textContent = `${file.name} — ${fileSize} MB`;

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
            submitBtn.style.opacity = '0.5';
            submitBtn.textContent = 'TRANSMITINDO...';

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                    progressPercent.textContent = percent + '%';
                }
            });

            xhr.addEventListener('load', function() {
                if (xhr.status === 200 || xhr.status === 204) {
                    window.location.href = '{{ route("dashboard") }}';
                } else {
                    alert('Erro no envio. Verifique o tamanho do arquivo (Máx 2MB) ou sua conexão.');
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.textContent = 'Enviar Comprovante Agora';
                    progressContainer.classList.add('hidden');
                }
            });

            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        });
    </script>
</x-app-layout>
