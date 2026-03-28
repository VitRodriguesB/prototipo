<x-mail::message>
# Pagamento Aprovado!

Olá, **{{ $payment->inscription->user->name }}**, tudo bem?

Temos ótimas notícias! O pagamento da sua inscrição para o evento **{{ $payment->inscription->event->title }}** foi validado com sucesso.

Sua participação está confirmada e seu ingresso digital já está disponível abaixo.

<div style="text-align: center; background-color: #ffffff; padding: 30px; border-radius: 20px; margin: 30px 0; border: 1px solid #e5e7eb;">
<img src="{{ $qrCodeImage }}" alt="Seu QR Code de Acesso" style="width: 250px; height: 250px; margin: 0 auto;">
<p style="margin-top: 15px; font-family: monospace; font-weight: bold; color: #4f46e5; font-size: 18px; letter-spacing: 2px;">
ID: {{ $payment->inscription->registration_code }}
</p>
<p style="font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px;">
Apresente este código na portaria do evento
</p>
</div>

**Detalhes do Evento:**
*   **Data:** {{ $payment->inscription->event->event_date->format('d/m/Y') }}
*   **Horário:** {{ $payment->inscription->event->event_date->format('H:i') }}h
*   **Local:** {{ $payment->inscription->event->location }}

Você também pode acessar seus certificados e submeter trabalhos (se permitido pela sua modalidade) diretamente pelo seu painel.

<x-mail::button :url="config('app.url') . '/dashboard'">
Acessar Meu Painel
</x-mail::button>

Nos vemos em breve,<br>
Equipe {{ config('app.name') }}
</x-mail::message>
