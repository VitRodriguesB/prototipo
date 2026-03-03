<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Payment $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
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
            ->subject('Pagamento Aprovado - ' . $event->title)
            ->greeting('Ótimas notícias, ' . $notifiable->name . '!')
            ->line('Seu pagamento foi **aprovado** e sua inscrição está confirmada.')
            ->line('**Evento:** ' . $event->title)
            ->line('**Data:** ' . $event->event_date->format('d/m/Y'))
            ->line('**Local:** ' . ($event->location ?? 'A definir'))
            ->line('---')
            ->line('Sua participação está garantida! Fique atento aos próximos comunicados.')
            ->action('Ver Detalhes', url('/dashboard'))
            ->line('Nos vemos em breve!')
            ->salutation('Atenciosamente, Equipe ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'event_title' => $this->payment->inscription->event->title,
        ];
    }
}
