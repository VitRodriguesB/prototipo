<?php

namespace App\Notifications;

use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Inscription $inscription;

    public function __construct(Inscription $inscription)
    {
        $this->inscription = $inscription;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Presença Confirmada - ' . $this->inscription->event->title)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Sua presença no evento **' . $this->inscription->event->title . '** foi confirmada com sucesso via QR Code.')
            ->line('Obrigado pela sua participação!')
            ->line('Seu certificado de participação já está disponível para download no seu painel.')
            ->action('Baixar Certificado', url('/dashboard'))
            ->line('Esperamos que o evento tenha sido proveitoso para sua carreira acadêmica.')
            ->salutation('Atenciosamente, Organização do Evento.');
    }
}