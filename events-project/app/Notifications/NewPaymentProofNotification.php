<?php

namespace App\Notifications;

use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPaymentProofNotification extends Notification implements ShouldQueue
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

        return (new MailMessage)
            ->subject('Novo Comprovante Recebido - ' . $event->title)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Um participante enviou um comprovante de pagamento para análise.')
            ->line('**Participante:** ' . $participant->name)
            ->line('**E-mail:** ' . $participant->email)
            ->line('**Evento:** ' . $event->title)
            ->line('**Tipo de Inscrição:** ' . $this->inscription->inscriptionType->name)
            ->line('---')
            ->line('Por favor, acesse o painel para aprovar ou recusar o pagamento.')
            ->action('Analisar Comprovante', route('organization.payments.index'))
            ->line('Lembre-se de verificar o comprovante em até 24 horas.')
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
