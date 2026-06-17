<?php

namespace App\Services;

use App\Models\NotificationRule;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketNotification;

class NotificationRuleService
{
    public function dispatch(Ticket $ticket, string $event): void
    {
        $rules = NotificationRule::query()
            ->where('event', $event)
            ->where('is_active', true)
            ->where(function ($query) use ($ticket) {
                $query
                    ->whereNull('service_item_id')
                    ->orWhere(
                        'service_item_id',
                        $ticket->service_item_id
                    );
            })
            ->get();

        foreach ($rules as $rule) {
            $recipient = $this->resolveRecipient(
                $ticket,
                $rule->recipient_type
            );

            if (! $recipient) {
                continue;
            }

            $channels = [];

            if ($rule->send_database) {
                $channels[] = 'database';
            }

            if ($rule->send_email) {
                $channels[] = 'mail';
            }

            if ($channels === []) {
                continue;
            }

            $message = $this->replaceVariables(
                $rule->message,
                $ticket,
                $recipient
            );

            $subject = $rule->subject
                ? $this->replaceVariables(
                    $rule->subject,
                    $ticket,
                    $recipient
                )
                : null;

            $recipient->notify(
                new TicketNotification(
                    ticket: $ticket,
                    message: $message,
                    channels: $channels,
                    subject: $subject,
                )
            );
        }
    }

    private function resolveRecipient(
        Ticket $ticket,
        string $recipientType
    ): ?User {
        return match ($recipientType) {
            'requester' => $ticket->requester,
            'reviewer' => $ticket->reviewer,
            default => null,
        };
    }

    private function replaceVariables(
        string $text,
        Ticket $ticket,
        User $recipient
    ): string {
        return str_replace(
            [
                '{{ ticket.number }}',
                '{{ ticket.title }}',
                '{{ ticket.status }}',
                '{{ requester.name }}',
                '{{ reviewer.name }}',
                '{{ recipient.name }}',
                '{{ service_item.name }}',
            ],
            [
                $ticket->ticket_number,
                $ticket->title,
                str_replace('_', ' ', $ticket->status),
                $ticket->requester->name,
                $ticket->reviewer?->name ?? 'Not assigned',
                $recipient->name,
                $ticket->serviceItem->name,
            ],
            $text
        );
    }
}
