<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Models\TicketApproval;
use App\Services\NotificationRuleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketApprovalController extends Controller
{
    public function store(
        Request $request,
        Ticket $ticket,
        NotificationRuleService $notificationRuleService
    ): RedirectResponse {
        $user = auth()->user();

        $canReview =
            $user->isAdmin()
            || $ticket->reviewer_id === $user->id;

        abort_unless($canReview, 403);

        if (in_array($ticket->status, [
            'approved',
            'rejected',
            'completed',
            'closed',
        ], true)) {
            return back()->withErrors([
                'decision' => 'This ticket already has a final decision.',
            ]);
        }

        $validated = $request->validate([
            'decision' => [
                'required',
                'in:approved,rejected,more_information_required',
            ],
            'comment' => [
                'nullable',
                'string',
                'required_if:decision,rejected,more_information_required',
            ],
        ]);

        $oldStatus = $ticket->status;

        $description = match ($validated['decision']) {
            'approved' => 'Ticket was approved.',
            'rejected' => 'Ticket was rejected.',
            'more_information_required' => 'More information was requested.',
        };

        DB::transaction(function () use (
            $ticket,
            $user,
            $validated,
            $oldStatus,
            $description
        ): void {
            TicketApproval::create([
                'ticket_id' => $ticket->id,
                'reviewer_id' => $user->id,
                'decision' => $validated['decision'],
                'comment' => $validated['comment'] ?? null,
            ]);

            $ticket->update([
                'status' => $validated['decision'],
            ]);

            TicketActivity::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'type' => 'review_decision',
                'description' => $description,
                'metadata' => [
                    'decision' => $validated['decision'],
                    'comment' => $validated['comment'] ?? null,
                ],
            ]);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'ticket_reviewed',
                'entity_type' => Ticket::class,
                'entity_id' => $ticket->id,
                'description' =>
                    "Ticket {$ticket->ticket_number} was {$validated['decision']}.",
                'old_values' => [
                    'status' => $oldStatus,
                ],
                'new_values' => [
                    'status' => $validated['decision'],
                    'comment' => $validated['comment'] ?? null,
                ],
            ]);
        });

        $ticket->refresh()->load([
            'requester',
            'reviewer',
            'serviceItem',
        ]);

        $event = match ($validated['decision']) {
            'approved' => 'ticket_approved',
            'rejected' => 'ticket_rejected',
            'more_information_required' => 'more_information_required',
        };

        $notificationRuleService->dispatch($ticket, $event);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Review decision saved successfully.');
    }
}
