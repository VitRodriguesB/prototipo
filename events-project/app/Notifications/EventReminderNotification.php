<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Event $event;
    protected int $daysUntil;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event, int $daysUntil)
    {
        $this->event = $event;
        $this->daysUntil = $daysUntil;
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
        $subject = $this->daysUntil === 1 
            ? 'Amanhã: ' . $this->event->title
            : "Faltam {$this->daysUntil} dias para " . $this->event->title;

        $urgencyLine = $this->daysUntil === 1
            ? '**O evento é amanhã!** Prepare-se para participar.'
            : "Faltam apenas **{$this->daysUntil} dias** para o início do evento.";

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line($urgencyLine)
            ->line('**Evento:** ' . $this->event->title)
            ->line('**Data:** ' . $this->event->event_date->format('d/m/Y'))
            ->line('**Local:** ' . ($this->event->location ?? 'A definir'))
            ->line('---')
            ->line('Não se esqueça de verificar a programação e suas atividades.')
            ->action('Ver Programação', url('/eventos/' . $this->event->id))
            ->line('Esperamos você lá!')
            ->salutation('Atenciosamente, Equipe ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'days_until' => $this->daysUntil,
        ];
    }
}
