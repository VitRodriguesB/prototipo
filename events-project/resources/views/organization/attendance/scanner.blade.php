<x-app-layout>
    <div class="bg-[#121214] min-h-screen flex flex-col items-center py-10 px-4 overflow-x-hidden w-full relative">
        
        {{-- Container de Toasts (Fixo no Topo) --}}
        <div id="toast-container" class="fixed top-4 left-4 right-4 z-[100] flex flex-col gap-3 pointer-events-none"></div>

        <!-- Cabeçalho de Identidade -->
        <div class="mb-10 text-center select-none">
            <h2 class="text-3xl font-black text-white uppercase italic text-center mb-2">
                SCANNER <span class="text-indigo-500">QR CODE</span>
            </h2>
            <p class="text-[10px] text-slate-500 uppercase tracking-[0.3em] font-black text-center">
                Controle de Presença Profissional
            </p>
        </div>

        <!-- Container Majestoso da Câmera -->
        <div class="w-full max-w-md bg-[#0a0a0a] border-2 border-indigo-500/30 rounded-[2.5rem] shadow-2xl shadow-indigo-500/20 overflow-hidden mb-8 relative">
            {{-- Linha de Escaneamento Animada --}}
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent blur-sm animate-scan z-20"></div>
            
            {{-- Div alvo da biblioteca de Scanner --}}
            <div id="reader" class="w-full h-auto aspect-square overflow-hidden"></div>
            
            {{-- Overlay de Foco --}}
            <div class="absolute inset-0 pointer-events-none border-[40px] border-black/40 z-10">
                <div class="w-full h-full border-2 border-white/10 rounded-3xl"></div>
            </div>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="w-full max-w-md grid grid-cols-1 gap-4 mb-8">
            <div class="p-6 bg-[#0a0a0a] border border-white/5 rounded-2xl text-center shadow-xl">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Presenças Confirmadas</p>
                <p id="attendanceCount" class="text-4xl font-black text-indigo-500 italic">0</p>
            </div>
        </div>

        <!-- Rodapé de Ações -->
        <div class="mt-auto">
            <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-white/5 text-slate-400 border border-white/10 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:text-white hover:bg-white/10 transition-all flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Voltar ao Painel
            </a>
        </div>
    </div>

    {{-- Biblioteca html5-qrcode --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    
    <script>
        let attendanceCount = 0;
        let isProcessing = false;

        function showToast(success, message, details = '') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const bgColor = success ? 'bg-emerald-500' : 'bg-red-600';
            const animation = success ? 'animate-bounce' : 'animate-shake';
            
            toast.className = `pointer-events-auto flex items-center p-5 mb-4 text-white rounded-2xl shadow-2xl border-2 border-white/20 transition-all duration-500 ${bgColor} ${animation}`;
            
            const icon = success 
                ? '<svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>'
                : '<svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path></svg>';

            toast.innerHTML = `
                ${icon}
                <div>
                    <p class="text-sm font-black uppercase tracking-tight">${message}</p>
                    ${details ? `<p class="text-[10px] font-bold opacity-80 uppercase mt-0.5">${details}</p>` : ''}
                </div>
            `;

            container.appendChild(toast);

            if (success) {
                attendanceCount++;
                document.getElementById('attendanceCount').textContent = attendanceCount;
            }

            // Remove o toast após 4 segundos
            setTimeout(() => {
                toast.classList.add('opacity-0', '-translate-y-4');
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;

            // Faz a requisição AJAX (FETCH) para a rota de validação
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
                    showToast(true, data.message, `Participante: ${data.participant}`);
                } else {
                    showToast(false, data.message, data.participant ? `Participante: ${data.participant}` : '');
                }
            })
            .catch(error => {
                showToast(false, 'Erro Crítico', 'Falha na comunicação com o servidor.');
            })
            .finally(() => {
                // Pequeno delay para evitar scans múltiplos do mesmo código instantaneamente
                setTimeout(() => { isProcessing = false; }, 2500);
            });
        }

        function onScanFailure(error) {
            //frames sem QR ignorados
        }

        const html5QrcodeScanner = new Html5Qrcode("reader");
        html5QrcodeScanner.start(
            { facingMode: "environment" }, 
            {
                fps: 15,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            document.getElementById('reader').innerHTML = `
                <div class="p-10 text-center bg-red-500/10 rounded-3xl border border-red-500/20">
                    <p class="text-red-500 font-black uppercase tracking-widest text-xs">Câmera Bloqueada</p>
                    <p class="text-[10px] text-slate-500 mt-2 font-bold uppercase">Verifique as permissões do navegador.</p>
                </div>
            `;
        });
    </script>

    <style>
        @keyframes scan {
            0% { top: 0; opacity: 0; }
            50% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .animate-scan { animation: scan 3s linear infinite; }
        .animate-shake { animation: shake 0.2s ease-in-out infinite; animation-iteration-count: 2; }
        
        /* Estilo do botão de câmera do html5-qrcode */
        #reader button {
            background-color: #4f46e5 !important;
            color: white !important;
            border: none !important;
            border-radius: 1rem !important;
            padding: 1rem 2rem !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            font-size: 10px !important;
            letter-spacing: 0.1em !important;
            cursor: pointer !important;
            margin-top: 2rem !important;
        }
    </style>
</x-app-layout>
