<?php

namespace App\Notifications;

use App\Models\Work;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Work $work;

    /**
     * Create a new notification instance.
     */
    public function __construct(Work $work)
    {
        $this->work = $work;
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
        $event = $this->work->event;

        return (new MailMessage)
            ->subject('Trabalho Recebido - ' . $event->title)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Seu trabalho foi submetido com sucesso.')
            ->line('**Título do Trabalho:** ' . $this->work->title)
            ->line('**Evento:** ' . $event->title)
            ->line('**Data de Submissão:** ' . $this->work->created_at->format('d/m/Y H:i'))
            ->line('---')
            ->line('Você receberá notificações sobre o status da avaliação.')
            ->action('Ver Meus Trabalhos', url('/dashboard'))
            ->line('Boa sorte!')
            ->salutation('Atenciosamente, Equipe ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'work_id' => $this->work->id,
            'work_title' => $this->work->title,
        ];
    }
}
