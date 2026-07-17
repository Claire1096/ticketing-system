<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class TicketInProgressNotification extends Notification
{
    use Queueable;

    public function __construct(public Ticket $ticket)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your ticket #' . str_pad($this->ticket->id, 4, '0', STR_PAD_LEFT) . ' is now in progress')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('Good news — IT Support has started working on your ticket.')
            ->line('Description: ' . Str::limit($this->ticket->description, 100))
            ->action('View Ticket', route('tickets.show', $this->ticket))
            ->line('We\'ll notify you again once it\'s resolved.')
            ->salutation('— EM Power Beautiful Skin IT Support');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'message' => 'Your ticket "' . Str::limit($this->ticket->description, 40) . '" is now in progress.',
        ];
    }
}