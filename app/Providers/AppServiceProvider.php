<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url = route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);

            return (new MailMessage)
                ->subject('Redefinição de Senha — Fire Out')
                ->greeting('Olá!')
                ->line('Você está recebendo este e-mail porque foi solicitada a redefinição de senha para a sua conta na Fire Out.')
                ->action('Redefinir Senha', $url)
                ->line('Este link de recuperação expirará em 60 minutos.')
                ->line('Se você não solicitou a redefinição de senha, nenhuma ação adicional é necessária.');
        });
    }
}
