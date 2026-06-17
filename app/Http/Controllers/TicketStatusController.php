<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Ticket;
use App\Models\TicketActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TicketStatusController extends Controller
{
    public function update(
        Request $request,
        Ticket $ticket
    ): RedirectResponse {
        $user = auth()->user();

        $canUpdate =
            $user->isAdmin()
            || $ticket->reviewer_id === $user->id;

        abort_unless($canUpdate, 403);

        $validated = $request->validate([
            'status' => [
                'required',
                'in:submitted,in_review,approved,rejected,more_information_required,in_progress,completed,closed',
            ],
        ]);

        $oldStatus = $ticket->status;
        $newStatus = $validated['status'];

        $ticket->update([
            'status' => $newStatus,
        ]);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'type' => 'status_changed',
            'description' => sprintf(
                'Status changed from %s to %s.',
                str_replace('_', ' ', $oldStatus),
                str_replace('_', ' ', $newStatus)
            ),
            'metadata' => [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'ticket_status_changed',
            'entity_type' => Ticket::class,
            'entity_id' => $ticket->id,
            'description' => "Status changed for ticket {$ticket->ticket_number}.",
            'old_values' => [
                'status' => $oldStatus,
            ],
            'new_values' => [
                'status' => $newStatus,
            ],
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket status updated successfully.');
    }
}
