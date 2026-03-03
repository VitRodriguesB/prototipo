<?php

namespace App\Notifications;

use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PendingPaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Inscription $inscription;

    /**
     * Create a new notification instance.
     */
    public function __construct(Inscription $inscription)
    {
        $this->inscription = $inscription;
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
        $participant = $this->inscription->user;
        $event = $this->inscription->event;
        $payment = $this->inscription->payment;
        $hoursAgo = $payment->created_at->diffInHours(now());

        return (new MailMessage)
            ->subject('Lembrete: Comprovante Aguardando Análise - ' . $event->title)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line("Há um comprovante de pagamento aguardando sua análise há mais de **{$hoursAgo} horas**.")
            ->line('**Participante:** ' . $participant->name)
            ->line('**E-mail:** ' . $participant->email)
            ->line('**Evento:** ' . $event->title)
            ->line('---')
            ->line('Por favor, analise o comprovante o mais breve possível.')
            ->action('Analisar Agora', route('organization.payments.index'))
            ->line('Este é um lembrete automático do sistema.')
            ->salutation('Atenciosamente, Sistema ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'inscription_id' => $this->inscription->id,
            'participant_name' => $this->inscription->user->name,
        ];
    }
}
