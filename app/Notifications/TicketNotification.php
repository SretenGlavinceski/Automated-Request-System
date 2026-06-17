<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class TicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private readonly string $trackingId;

    public function __construct(
        private readonly Ticket $ticket,
        private readonly string $message,
        private readonly array $channels = ['database'],
        private readonly ?string $subject = null,
    ) {
        $this->trackingId = (string) Str::uuid();
    }

    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(
                $this->subject
                ?? "Ticket {$this->ticket->ticket_number} updated"
            )
            ->greeting("Hello {$notifiable->name},")
            ->line($this->message)
            ->line("Ticket: {$this->ticket->title}")
            ->action(
                'Open Ticket',
                route('tickets.show', $this->ticket)
            );
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'message' => $this->message,
            'url' => route('tickets.show', $this->ticket),
        ];
    }

    public function ticket(): Ticket
    {
        return $this->ticket;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function trackingId(): string
    {
        return $this->trackingId;
    }
}
