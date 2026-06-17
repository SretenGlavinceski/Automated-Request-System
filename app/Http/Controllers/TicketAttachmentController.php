<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Models\TicketAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TicketAttachmentController extends Controller
{
    public function store(
        Request $request,
        Ticket $ticket
    ): RedirectResponse {
        $user = auth()->user();

        $canAccess =
            $user->isAdmin()
            || $ticket->requester_id === $user->id
            || $ticket->reviewer_id === $user->id;

        abort_unless($canAccess, 403);

        $validated = $request->validate([
            'attachment' => [
                'required',
                'file',
                'max:5120',
                'mimes:pdf,doc,docx,jpg,jpeg,png,txt',
            ],
        ]);

        $file = $validated['attachment'];

        $path = $file->store(
            "ticket-attachments/{$ticket->id}",
            'local'
        );

        $attachment = TicketAttachment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => basename($path),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'type' => 'attachment_added',
            'description' => 'An attachment was added.',
            'metadata' => [
                'attachment_id' => $attachment->id,
                'filename' => $attachment->original_name,
            ],
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'ticket_attachment_added',
            'entity_type' => Ticket::class,
            'entity_id' => $ticket->id,
            'description' => "An attachment was added to ticket {$ticket->ticket_number}.",
            'old_values' => null,
            'new_values' => [
                'filename' => $attachment->original_name,
                'size' => $attachment->size,
            ],
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Attachment uploaded successfully.');
    }

    public function download(
        Ticket $ticket,
        TicketAttachment $attachment
    ): StreamedResponse {
        $user = auth()->user();

        $canAccess =
            $user->isAdmin()
            || $ticket->requester_id === $user->id
            || $ticket->reviewer_id === $user->id;

        abort_unless($canAccess, 403);
        abort_unless($attachment->ticket_id === $ticket->id, 404);
        abort_unless(Storage::disk('local')->exists($attachment->path), 404);

        return Storage::disk('local')->download(
            $attachment->path,
            $attachment->original_name
        );
    }
}
