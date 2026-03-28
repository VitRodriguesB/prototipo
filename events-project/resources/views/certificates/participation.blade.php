<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $event->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; padding: 0 !important; }
            .a4-landscape { box-shadow: none !important; margin: 0 !important; }
        }

        body {
            background-color: #121214;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 2rem;
        }

        .a4-landscape {
            width: 297mm;
            height: 210mm;
            background-color: white;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            padding: 40px;
        }

        /* Design do Certificado */
        .decorative-border {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 4px solid #4f46e5;
            border-radius: 4px;
            pointer-events: none;
        }

        .decorative-border::after {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 1px solid #9333ea;
        }

        .certificate-content {
            position: relative;
            z-index: 10;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 80px;
        }

        .cert-header h1 {
            font-family: serif;
            font-size: 56px;
            font-weight: 900;
            color: #1e1b4b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0;
        }

        .cert-header p {
            font-size: 18px;
            color: #4338ca;
            font-weight: 700;
            letter-spacing: 0.4em;
            margin-bottom: 40px;
        }

        .cert-body p {
            font-size: 20px;
            color: #4b5563;
            line-height: 1.6;
        }

        .participant-name {
            font-size: 48px;
            font-weight: 900;
            color: #111827;
            margin: 30px 0;
            font-family: serif;
            border-bottom: 2px solid #e5e7eb;
            display: inline-block;
            padding-bottom: 10px;
        }

        .cert-footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature {
            text-align: center;
            width: 300px;
        }

        .signature-line {
            border-top: 2px solid #1f2937;
            margin-bottom: 10px;
        }

        .signature p {
            font-size: 14px;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
        }

        .auth-info {
            text-align: right;
        }

        .auth-info p {
            font-size: 10px;
            color: #9ca3af;
            margin: 2px 0;
        }

        .auth-code {
            font-family: monospace;
            font-weight: bold;
            color: #4f46e5;
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800">

    <!-- Interface Híbrida: Botões de Ação -->
    <div class="no-print fixed top-6 right-6 z-50 flex gap-4">
        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-white/20 transition-all shadow-xl">
            Voltar ao Painel
        </a>
        <button onclick="window.print()" class="px-6 py-3 bg-gradient-to-r from-[#4f46e5] to-[#9333ea] text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">
            Imprimir PDF
        </button>
    </div>

    <!-- Documento Físico (A4 Landscape) -->
    <div class="a4-landscape">
        <div class="decorative-border"></div>
        
        <div class="certificate-content">
            <div class="cert-header">
                <h1>Certificado</h1>
                <p>
                    @if($type === 'participation')
                        DE PARTICIPAÇÃO
                    @else
                        DE APRESENTAÇÃO DE TRABALHO
                    @endif
                </p>
            </div>

            <div class="cert-body">
                <p>Certificamos, para os devidos fins, que</p>
                <div class="participant-name">{{ $user->name }}</div>
                
                @if($type === 'participation')
                    <p>
                        participou do evento <strong>{{ $event->title }}</strong>, 
                        realizado em <strong>{{ $event->location }}</strong> no dia {{ $event->event_date->format('d/m/Y') }}, 
                        cumprindo integralmente a carga horária de <strong>{{ $event->workload ?? '20' }} horas</strong>.
                    </p>
                @else
                    <p>
                        apresentou o trabalho científico intitulado <strong>"{{ $work->title }}"</strong> 
                        no evento <strong>{{ $event->title }}</strong>, 
                        realizado em <strong>{{ $event->location }}</strong> no dia {{ $event->event_date->format('d/m/Y') }}.
                    </p>
                @endif
            </div>

            <div class="cert-footer">
                <div class="signature">
                    <div class="signature-line"></div>
                    <p>Organização do Evento</p>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $event->title }}</span>
                </div>

                <div class="auth-info">
                    <p>Autenticidade garantida pelo código:</p>
                    <p class="auth-code">{{ $authCode }}</p>
                    <p>Emitido em {{ now()->format('d/m/Y \à\s H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
