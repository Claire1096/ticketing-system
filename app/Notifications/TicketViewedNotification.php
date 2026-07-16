<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketViewedNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $technicianName;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, $technicianName)
    {
        $this->ticket = $ticket;
        $this->technicianName = $technicianName;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'message' => "Your ticket regarding '" . \Str::limit($this->ticket->description, 30) . "' has been viewed and is now being reviewed by " . $this->technicianName . ".",
            'viewed_by' => $this->technicianName,
            'action_url' => route('tickets.show', $this->ticket->id),
        ];
    }
}<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketViewedNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $technicianName;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, $technicianName)
    {
        $this->ticket = $ticket;
        $this->technicianName = $technicianName;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'message' => "Your ticket regarding '" . \Str::limit($this->ticket->description, 30) . "' has been viewed and is now being reviewed by " . $this->technicianName . ".",
            'viewed_by' => $this->technicianName,
            'action_url' => route('tickets.show', $this->ticket->id),
        ];
    }
}