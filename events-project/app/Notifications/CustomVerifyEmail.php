<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $verificationUrl = $this->verificationUrl($notifiable);
        $expirationMinutes = Config::get('auth.verification.expire', 15);

        return (new MailMessage)
            ->subject('Confirme seu Cadastro - ' . config('app.name'))
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Obrigado por se cadastrar em nossa plataforma de eventos.')
            ->line('Para ativar sua conta e ter acesso completo ao sistema, clique no botão abaixo:')
            ->action('Confirmar E-mail', $verificationUrl)
            ->line("**Atenção:** Este link expira em **{$expirationMinutes} minutos**.")
            ->line('Se você não criou uma conta, nenhuma ação é necessária.')
            ->salutation('Atenciosamente, Equipe ' . config('app.name'));
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl(object $notifiable): string
    {
        $expirationMinutes = Config::get('auth.verification.expire', 15);

        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes($expirationMinutes),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
