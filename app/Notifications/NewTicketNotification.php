<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewTicketNotification extends Notification
{
    use Queueable;

    public function __construct(public Ticket $ticket)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
{
    return [
        'ticket_id' => $this->ticket->id,
        'priority' => $this->ticket->priority,
        'message' => $this->ticket->submittedBy->name . ' submitted a new ' . $this->ticket->priority . ' priority ticket: "' . \Illuminate\Support\Str::limit($this->ticket->description, 40) . '"',
    ];
}
}