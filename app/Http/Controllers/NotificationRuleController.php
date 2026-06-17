<?php

namespace App\Http\Controllers;

use App\Models\NotificationRule;
use App\Models\ServiceItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationRuleController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $notificationRules = NotificationRule::with('serviceItem')
            ->latest()
            ->get();

        return view(
            'notification-rules.index',
            compact('notificationRules')
        );
    }

    public function create(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $serviceItems = ServiceItem::orderBy('name')->get();

        return view(
            'notification-rules.create',
            compact('serviceItems')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'service_item_id' => [
                'nullable',
                'exists:service_items,id',
            ],
            'event' => [
                'required',
                'in:ticket_created,ticket_approved,ticket_rejected,more_information_required,comment_added',
            ],
            'recipient_type' => [
                'required',
                'in:requester,reviewer',
            ],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        NotificationRule::create([
            'service_item_id' => $validated['service_item_id'] ?? null,
            'event' => $validated['event'],
            'recipient_type' => $validated['recipient_type'],
            'send_database' => $request->boolean('send_database'),
            'send_email' => $request->boolean('send_email'),
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('notification-rules.index')
            ->with('success', 'Notification rule created successfully.');
    }

    public function edit(NotificationRule $notificationRule): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $serviceItems = ServiceItem::orderBy('name')->get();

        return view(
            'notification-rules.edit',
            compact('notificationRule', 'serviceItems')
        );
    }

    public function update(
        Request $request,
        NotificationRule $notificationRule
    ): RedirectResponse {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'service_item_id' => [
                'nullable',
                'exists:service_items,id',
            ],
            'event' => [
                'required',
                'in:ticket_created,ticket_approved,ticket_rejected,more_information_required,comment_added',
            ],
            'recipient_type' => [
                'required',
                'in:requester,reviewer',
            ],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $notificationRule->update([
            'service_item_id' => $validated['service_item_id'] ?? null,
            'event' => $validated['event'],
            'recipient_type' => $validated['recipient_type'],
            'send_database' => $request->boolean('send_database'),
            'send_email' => $request->boolean('send_email'),
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('notification-rules.index')
            ->with('success', 'Notification rule updated successfully.');
    }

    public function destroy(
        NotificationRule $notificationRule
    ): RedirectResponse {
        abort_unless(auth()->user()->isAdmin(), 403);

        $notificationRule->delete();

        return redirect()
            ->route('notification-rules.index')
            ->with('success', 'Notification rule deleted successfully.');
    }
}
