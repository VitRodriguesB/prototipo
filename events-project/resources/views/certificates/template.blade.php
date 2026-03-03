<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificado - {{ $event->title }}</title>
    <style>
        @page {
            size: landscape;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 100%;
            height: 100%;
        }
        
        .certificate {
            width: 100%;
            height: 100%;
            padding: 30px;
            position: relative;
        }
        
        .certificate-inner {
            background: white;
            width: 100%;
            height: 100%;
            border-radius: 20px;
            padding: 40px;
            position: relative;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        /* Bordas decorativas */
        .border-decoration {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid #667eea;
            border-radius: 15px;
            pointer-events: none;
        }
        
        .border-decoration::before {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border: 1px solid #764ba2;
            border-radius: 12px;
        }
        
        /* Conteúdo */
        .content {
            text-align: center;
            padding: 20px 60px;
            position: relative;
            z-index: 1;
        }
        
        .header {
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 42px;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 8px;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 16px;
            color: #764ba2;
            letter-spacing: 4px;
        }
        
        .main-text {
            margin: 25px 0;
        }
        
        .main-text p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }
        
        .participant-name {
            font-size: 32px;
            font-weight: bold;
            color: #2d3748;
            margin: 15px 0;
            padding: 10px 0;
            border-bottom: 2px solid #667eea;
            display: inline-block;
        }
        
        .event-details {
            margin: 20px 0;
            padding: 15px;
            background: linear-gradient(90deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
            border-radius: 10px;
        }
        
        .event-details h2 {
            font-size: 22px;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .event-details p {
            font-size: 14px;
            color: #4a5568;
            margin: 5px 0;
        }
        
        .work-title {
            font-size: 18px;
            font-style: italic;
            color: #764ba2;
            margin: 15px 0;
            padding: 10px 20px;
            background: rgba(118,75,162,0.1);
            border-radius: 8px;
            display: inline-block;
        }
        
        .footer {
            position: absolute;
            bottom: 40px;
            left: 60px;
            right: 60px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        
        .signature {
            text-align: center;
            width: 250px;
        }
        
        .signature-line {
            border-top: 2px solid #333;
            margin-bottom: 5px;
        }
        
        .signature p {
            font-size: 12px;
            color: #4a5568;
        }
        
        .auth-code {
            text-align: right;
        }
        
        .auth-code p {
            font-size: 10px;
            color: #718096;
        }
        
        .auth-code .code {
            font-family: monospace;
            font-size: 12px;
            color: #667eea;
            background: #f7fafc;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="certificate-inner">
            <div class="border-decoration"></div>
            
            <div class="content">
                <div class="header">
                    <h1>Certificado</h1>
                    <p class="subtitle">
                        @if($type === 'participation')
                            DE PARTICIPAÇÃO
                        @else
                            DE APRESENTAÇÃO DE TRABALHO
                        @endif
                    </p>
                </div>
                
                <div class="main-text">
                    <p>Certificamos que</p>
                    <div class="participant-name">{{ $user->name }}</div>
                    <p>
                        @if($type === 'participation')
                            participou do evento abaixo relacionado, cumprindo integralmente a carga horária estabelecida.
                        @else
                            apresentou trabalho acadêmico no evento abaixo relacionado.
                        @endif
                    </p>
                </div>
                
                <div class="event-details">
                    <h2>{{ $event->title }}</h2>
                    <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}</p>
                    <p><strong>Local:</strong> {{ $event->location ?? 'Não informado' }}</p>
                    @if($event->workload)
                        <p><strong>Carga Horária:</strong> {{ $event->workload }} horas</p>
                    @endif
                </div>
                
                @if($type === 'presentation' && $work)
                    <div class="work-title">
                        "{{ $work->title }}"
                    </div>
                @endif
            </div>
            
            <div class="footer">
                <div class="signature">
                    <div class="signature-line"></div>
                    <p>Organização do Evento</p>
                </div>
                
                <div class="auth-code">
                    <p>Código de Autenticação</p>
                    <div class="code">{{ $authCode }}</div>
                    <p style="margin-top: 5px;">Emitido em: {{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
