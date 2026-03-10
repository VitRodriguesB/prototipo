<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Comprovante de Inscrição - {{ $inscription->registration_code }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #4f46e5;
            text-transform: uppercase;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .data-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .value {
            display: inline-block;
        }
        .registration-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            text-align: center;
            margin-top: 40px;
        }
        .registration-code {
            font-size: 32px;
            font-weight: bold;
            color: #1e293b;
            letter-spacing: 5px;
        }
        .footer {
            margin-top: 100px;
            font-size: 10px;
            text-align: center;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Comprovante de Inscrição</h1>
        <p>Sistema de Gestão de Eventos Acadêmicos</p>
    </div>

    <div class="section">
        <div class="section-title">Dados do Participante</div>
        <div class="data-row">
            <span class="label">Nome:</span>
            <span class="value">{{ $user->name }}</span>
        </div>
        <div class="data-row">
            <span class="label">E-mail:</span>
            <span class="value">{{ $user->email }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Dados do Evento</div>
        <div class="data-row">
            <span class="label">Evento:</span>
            <span class="value">{{ $event->title }}</span>
        </div>
        <div class="data-row">
            <span class="label">Data:</span>
            <span class="value">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y \à\s H:i') }}</span>
        </div>
        <div class="data-row">
            <span class="label">Local:</span>
            <span class="value">{{ $event->location }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Dados da Inscrição</div>
        <div class="data-row">
            <span class="label">Modalidade:</span>
            <span class="value">{{ $inscription->inscriptionType->type }}</span>
        </div>
        <div class="data-row">
            <span class="label">Status:</span>
            <span class="value">{{ $inscription->status == 1 ? 'CONFIRMADA' : 'PENDENTE DE PAGAMENTO' }}</span>
        </div>
        <div class="data-row">
            <span class="label">Data da Inscrição:</span>
            <span class="value">{{ $inscription->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <div class="registration-box">
        <div style="font-size: 10px; color: #64748b; margin-bottom: 5px;">CÓDIGO DE REGISTRO</div>
        <div class="registration-code">{{ $inscription->registration_code }}</div>
    </div>

    <div class="footer">
        Este documento é uma confirmação eletrônica gerada em {{ now()->format('d/m/Y H:i:s') }}.<br>
        Autenticidade vinculada ao registro interno do sistema.
    </div>
</body>
</html>