<?php

namespace App\Http\Controllers;

use App\Models\ServiceItem;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\TicketActivity;
use App\Models\AuditLog;
use App\Services\NotificationRuleService;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $view = $request->query('view', 'mine');

        $query = Ticket::with([
            'requester',
            'reviewer',
            'serviceItem',
        ]);

        if ($view === 'review') {
            abort_unless($user->canReviewTickets(), 403);

            $query->where('reviewer_id', $user->id);
        } elseif ($view === 'all') {
            abort_unless($user->isAdmin(), 403);
        } else {
            $query->where('requester_id', $user->id);
            $view = 'mine';
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($query) use ($search) {
                $query
                    ->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $tickets = $query
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets', 'view'));
    }

    public function create(): View
    {
        $serviceItems = ServiceItem::where('is_active', true)
            ->orderBy('name')
            ->get();

        $reviewers = User::whereIn('role', ['reviewer', 'admin'])
            ->orderBy('name')
            ->get();

        return view('tickets.create', compact('serviceItems', 'reviewers'));
    }

    public function store(
        Request $request,
        NotificationRuleService $notificationRuleService
    ): RedirectResponse
    {
        $validated = $request->validate([
            'service_item_id' => ['required', 'exists:service_items,id'],
            'reviewer_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:low,normal,high,critical'],
        ]);

        $ticket = Ticket::create([
            'ticket_number' => $this->generateTicketNumber(),
            'requester_id' => auth()->id(),
            'service_item_id' => $validated['service_item_id'],
            'reviewer_id' => $validated['reviewer_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'submitted',
            'priority' => $validated['priority'],
        ]);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'type' => 'ticket_created',
            'description' => 'Ticket was created.',
            'metadata' => [
                'status' => $ticket->status,
                'reviewer_id' => $ticket->reviewer_id,
            ],
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'ticket_created',
            'entity_type' => Ticket::class,
            'entity_id' => $ticket->id,
            'description' => "Ticket {$ticket->ticket_number} was created.",
            'old_values' => null,
            'new_values' => [
                'ticket_number' => $ticket->ticket_number,
                'service_item_id' => $ticket->service_item_id,
                'reviewer_id' => $ticket->reviewer_id,
                'status' => $ticket->status,
                'priority' => $ticket->priority,
            ],
        ]);

        $ticket->load([
            'requester',
            'reviewer',
            'serviceItem',
        ]);

        $notificationRuleService->dispatch(
            $ticket,
            'ticket_created'
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket): View
    {
        $this->authorizeView($ticket);

        $ticket->load([
            'requester',
            'reviewer',
            'serviceItem',
            'approvals.reviewer',
            'comments.user',
            'activities.user',
            'attachments.user',
        ]);

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket): never
    {
        abort(404);
    }

    public function update(Request $request, Ticket $ticket): never
    {
        abort(404);
    }

    public function destroy(Ticket $ticket): never
    {
        abort(404);
    }

    private function generateTicketNumber(): string
    {
        $nextId = (Ticket::max('id') ?? 0) + 1;

        return 'REQ-' . str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
    }

    private function authorizeView(Ticket $ticket): void
    {
        $user = auth()->user();

        $canView =
            $user->isAdmin()
            || $ticket->requester_id === $user->id
            || $ticket->reviewer_id === $user->id;

        abort_unless($canView, 403);
    }
}
