<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Payment $payment;
    protected string $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment, string $reason = '')
    {
        $this->payment = $payment;
        $this->reason = $reason ?: 'Motivo não especificado';
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $inscription = $this->payment->inscription;
        $event = $inscription->event;

        return (new MailMessage)
            ->subject('Pagamento Recusado - ' . $event->title)
            ->greeting('Olá, ' . $notifiable->name)
            ->line('Infelizmente, seu pagamento para o evento foi **recusado**.')
            ->line('**Evento:** ' . $event->title)
            ->line('**Motivo da Recusa:** ' . $this->reason)
            ->line('---')
            ->line('Não se preocupe! Você pode enviar um novo comprovante.')
            ->action('Enviar Novo Comprovante', route('payment.create', $inscription))
            ->line('Se tiver dúvidas, entre em contato com a organização do evento.')
            ->salutation('Atenciosamente, Equipe ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'reason' => $this->reason,
        ];
    }
}
