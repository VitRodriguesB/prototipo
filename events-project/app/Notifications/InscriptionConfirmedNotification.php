<?php

namespace App\Notifications;

use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscriptionConfirmedNotification extends Notification implements ShouldQueue
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
        $event = $this->inscription->event;
        $inscriptionType = $this->inscription->inscriptionType;

        return (new MailMessage)
            ->subject('Inscrição Confirmada - ' . $event->title)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Sua inscrição no evento foi realizada com sucesso.')
            ->line('**Evento:** ' . $event->title)
            ->line('**Tipo de Inscrição:** ' . $inscriptionType->name)
            ->line('**Data do Evento:** ' . $event->event_date->format('d/m/Y'))
            ->line('---')
            ->line('**Próximo passo:** Realize o pagamento para confirmar sua participação.')
            ->action('Acessar Minha Inscrição', url('/dashboard'))
            ->line('Aguardamos você no evento!')
            ->salutation('Atenciosamente, Equipe ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'inscription_id' => $this->inscription->id,
            'event_title' => $this->inscription->event->title,
        ];
    }
}
