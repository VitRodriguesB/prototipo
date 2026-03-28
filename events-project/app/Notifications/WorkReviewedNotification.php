<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkReviewedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Review $review;

    /**
     * Create a new notification instance.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
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
        $work = $this->review->work;
        $status = $this->review->status == 1 ? 'APROVADO' : 'REPROVADO';
        $color = $this->review->status == 1 ? '#10b981' : '#ef4444';

        return (new MailMessage)
            ->subject('Resultado da Avaliação: ' . $work->title)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('O Comitê Científico finalizou a avaliação do seu trabalho acadêmico.')
            ->line('**Trabalho:** ' . $work->title)
            ->line('**Status Final:** ' . $status)
            ->line('---')
            ->line('**Parecer do Avaliador:**')
            ->line('"' . $this->review->comments . '"')
            ->line('---')
            ->action('Acessar Painel', url('/dashboard'))
            ->line('Caso seu trabalho tenha sido aprovado, fique atento à agenda de apresentações no seu painel.')
            ->salutation('Atenciosamente, Comitê Científico PÁTIO');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'work_id' => $this->review->work_id,
            'status' => $this->review->status,
        ];
    }
}
