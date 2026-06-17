<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Models\TicketComment;
use App\Services\NotificationRuleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    public function store(
        Request $request,
        Ticket $ticket,
        NotificationRuleService $notificationRuleService
    ): RedirectResponse {
        $user = auth()->user();

        $canView =
            $user->isAdmin()
            || $ticket->requester_id === $user->id
            || $ticket->reviewer_id === $user->id;

        abort_unless($canView, 403);

        $validated = $request->validate([
            'comment' => ['required', 'string'],
            'is_internal' => ['nullable', 'boolean'],
        ]);

        $isInternal = false;

        if ($user->canReviewTickets()) {
            $isInternal = $request->boolean('is_internal');
        }

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'comment' => $validated['comment'],
            'is_internal' => $isInternal,
        ]);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'type' => $isInternal
                ? 'internal_note_added'
                : 'comment_added',
            'description' => $isInternal
                ? 'An internal note was added.'
                : 'A comment was added.',
            'metadata' => [
                'comment' => $validated['comment'],
                'is_internal' => $isInternal,
            ],
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => $isInternal
                ? 'ticket_internal_note_added'
                : 'ticket_comment_added',
            'entity_type' => Ticket::class,
            'entity_id' => $ticket->id,
            'description' => $isInternal
                ? "An internal note was added to ticket {$ticket->ticket_number}."
                : "A comment was added to ticket {$ticket->ticket_number}.",
            'old_values' => null,
            'new_values' => [
                'comment' => $validated['comment'],
                'is_internal' => $isInternal,
            ],
        ]);

        if (! $isInternal) {
            $ticket->load([
                'requester',
                'reviewer',
                'serviceItem',
            ]);

            $notificationRuleService->dispatch(
                $ticket,
                'comment_added'
            );
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Comment added successfully.');
    }
}
