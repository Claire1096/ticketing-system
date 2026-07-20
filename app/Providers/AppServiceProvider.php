<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

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
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->subject('Reset Your IT Support Password')
                ->greeting('Hello ' . $notifiable->name . ',')
                ->line('We received a request to reset your IT Support account password.')
                ->action('Reset Password', url(route('password.reset', ['token' => $token, 'email' => $notifiable->email], false)))
                ->line('If you didn\'t request this, no action is needed.')
                ->salutation('— EM Power Beautiful Skin IT Support')
                ->withSymfonyMessage(function ($message) {
                    $message->cc('itsupport@bbic.com.ph');
                });
        });
    }
}