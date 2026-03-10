<?php

namespace App\Notifications;

use App\Models\Work;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkScheduledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Work $work;

    public function __construct(Work $work)
    {
        $this->work = $work;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Agenda de Apresentação Definida - ' . $this->work->title)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('A agenda para a apresentação do seu trabalho acadêmico foi definida.')
            ->line('**Trabalho:** ' . $this->work->title)
            ->line('**Data e Hora:** ' . $this->work->presentation_date->format('d/m/Y \à\s H:i'))
            ->line('**Local/Sala:** ' . $this->work->presentation_room)
            ->line('**Ordem de Apresentação:** #' . $this->work->presentation_order)
            ->line('Prepare sua apresentação e boa sorte!')
            ->action('Ver Painel do Participante', url('/dashboard'))
            ->salutation('Atenciosamente, Coordenação do Evento.');
    }
}