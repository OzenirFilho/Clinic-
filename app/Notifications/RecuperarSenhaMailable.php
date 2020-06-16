<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecuperarSenhaMailable extends Notification implements ShouldQueue
{
    use Queueable;
    protected $usuario;
    protected $senha;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->usuario=$mailData['usuario'];
        $this->senha=$mailData['senha'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Recuperação de senha')
                    ->subject('Atestado Válido - Recuperar Senha')
                    ->from('test@example.com', 'Atestado Válido')
                    ->line("**$this->usuario**, Você solicitou uma recuperação de senha no nosso sistema.")
                    ->line('Clique no botão abaixo e digite a senha temporária para recuperar sua conta.')
                    ->line("Sua senha temporária é: $this->senha")
                    ->action('Recuperar minha conta!', route('login'))
                    ->salutation('Obrigado pela atenção.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
