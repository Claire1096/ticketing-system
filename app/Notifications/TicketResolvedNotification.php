<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketResolvedNotification extends Notification
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
            ->subject('Your ticket #' . str_pad($this->ticket->id, 4, '0', STR_PAD_LEFT) . ' has been resolved')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('Your IT support ticket has been marked as resolved.')
            ->line('Description: ' . \Illuminate\Support\Str::limit($this->ticket->description, 100))
            ->line('Technician remarks: ' . ($this->ticket->technician_remarks ?: 'None provided.'))
            ->action('View Ticket', route('tickets.show', $this->ticket))
            ->line('If this issue isn\'t actually fixed, feel free to reply or submit a new ticket.')
            ->salutation('— EM Power Beautiful Skin IT Support');
    }

    public function toArray(object $notifiable): array
{
    return [
        'ticket_id' => $this->ticket->id,
        'message' => 'Your ticket "' . \Illuminate\Support\Str::limit($this->ticket->description, 40) . '" has been resolved.',
    ];
}
}