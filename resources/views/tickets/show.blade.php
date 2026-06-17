@extends('layouts.app')

@section('title', $ticket->ticket_number . ' - RequestHub')

@section('content')
    @php
        $isFinalState = in_array($ticket->status, ['approved', 'rejected', 'completed', 'closed'], true);

        $canManageStatus = (
            auth()->user()->isAdmin()
            || $ticket->reviewer_id === auth()->id()
        )
        && ! $isFinalState;

        $canReviewTicket = (
            auth()->user()->isAdmin()
            || $ticket->reviewer_id === auth()->id()
        )
        && ! $isFinalState;

        $statusOptions = [
            'submitted' => 'Submitted',
            'in_review' => 'In review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'more_information_required' => 'More information required',
            'in_progress' => 'In progress',
            'completed' => 'Completed',
            'closed' => 'Closed',
        ];

        $visibleComments = $ticket->comments->filter(
            function ($comment) {
                return ! $comment->is_internal
                    || auth()->user()->canReviewTickets();
            }
        );

        $visibleActivities = $ticket->activities->filter(
            function ($activity) {
                return ! ($activity->metadata['is_internal'] ?? false)
                    || auth()->user()->canReviewTickets();
            }
        );
    @endphp

    @include('partials.page-header', [
        'title' => $ticket->ticket_number,
        'subtitle' => $ticket->title,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Tickets', 'url' => route('tickets.index', ['view' => 'mine'])],
            ['label' => $ticket->ticket_number, 'url' => route('tickets.show', $ticket), 'current' => true],
        ],
        'actions' => '<a href="' . route('tickets.index', ['view' => 'mine']) . '" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Back to Tickets</a>',
    ])

    <div class="row g-4">
        <div class="col-12 col-xl-8 order-2 order-xl-1">
            <section class="card app-surface mb-4" aria-labelledby="description-heading">
                <div class="card-body p-3 p-md-4">
                    <h2 id="description-heading" class="h5 mb-3">Description</h2>
                    <p class="mb-0 text-break">{{ $ticket->description }}</p>
                </div>
            </section>

            <section class="card app-surface mb-4" aria-labelledby="comments-heading">
                <div class="card-body p-3 p-md-4">
                    <h2 id="comments-heading" class="h5 mb-3">Comments</h2>

                    @if ($visibleComments->isEmpty())
                        <p class="text-muted mb-4">No comments yet.</p>
                    @else
                        <div class="d-flex flex-column gap-3 mb-4">
                            @foreach ($visibleComments as $comment)
                                <article class="ticket-comment-item {{ $comment->is_internal ? 'ticket-comment-internal' : 'ticket-comment-public' }}">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <strong>{{ $comment->user->name }}</strong>
                                        @if ($comment->is_internal)
                                            <span class="badge text-bg-warning-subtle border border-warning-subtle text-warning-emphasis">Internal note</span>
                                        @endif
                                        <small class="text-muted ms-auto">{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                    <p class="mb-0 text-break">{{ $comment->comment }}</p>
                                </article>
                            @endforeach
                        </div>
                    @endif

                    <h3 class="h6 mb-3">Add comment</h3>

                    <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="ticket_comment" class="form-label">Comment</label>
                            <textarea
                                id="ticket_comment"
                                name="comment"
                                rows="4"
                                class="form-control @error('comment') is-invalid @enderror"
                                required
                            >{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        @if (auth()->user()->canReviewTickets())
                            <div class="form-check mb-3">
                                <input
                                    id="is_internal"
                                    type="checkbox"
                                    name="is_internal"
                                    value="1"
                                    class="form-check-input"
                                    @checked(old('is_internal'))
                                >
                                <label for="is_internal" class="form-check-label">Internal note</label>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-sm btn-primary">Add comment</button>
                    </form>
                </div>
            </section>

            <section class="card app-surface mb-4" aria-labelledby="attachments-heading">
                <div class="card-body p-3 p-md-4">
                    <h2 id="attachments-heading" class="h5 mb-3">Attachments</h2>

                    @if ($ticket->attachments->isEmpty())
                        <p class="text-muted mb-4">No attachments uploaded.</p>
                    @else
                        <div class="list-group list-group-flush mb-4">
                            @foreach ($ticket->attachments as $attachment)
                                <article class="list-group-item px-0 py-2">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                        <a href="{{ route('tickets.attachments.download', [$ticket, $attachment]) }}" class="text-decoration-none fw-semibold">
                                            <i class="bi bi-paperclip me-1" aria-hidden="true"></i>
                                            {{ $attachment->original_name }}
                                        </a>
                                        <small class="text-muted">{{ number_format($attachment->size / 1024, 1) }} KB</small>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Uploaded by {{ $attachment->user->name }} on {{ $attachment->created_at->format('d.m.Y H:i') }}
                                    </small>
                                </article>
                            @endforeach
                        </div>
                    @endif

                    <h3 class="h6 mb-3">Upload attachment</h3>

                    <form method="POST" action="{{ route('tickets.attachments.store', $ticket) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="attachment" class="form-label">Select file</label>
                            <input
                                id="attachment"
                                type="file"
                                name="attachment"
                                class="form-control @error('attachment') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt"
                                required
                            >
                            <div class="form-text">Allowed: PDF, DOC, DOCX, JPG, PNG, TXT. Maximum size: 5 MB.</div>
                            @error('attachment')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm btn-outline-primary">Upload attachment</button>
                    </form>
                </div>
            </section>

            <section class="card app-surface" aria-labelledby="timeline-heading">
                <div class="card-body p-3 p-md-4">
                    <h2 id="timeline-heading" class="h5 mb-3">Timeline</h2>

                    @if ($visibleActivities->isEmpty())
                        <p class="text-muted mb-0">No activity yet.</p>
                    @else
                        <div class="ticket-timeline">
                            @foreach ($visibleActivities as $activity)
                                <article class="ticket-timeline-item">
                                    <span class="ticket-timeline-marker" aria-hidden="true"></span>
                                    <div class="ticket-timeline-content">
                                        <div class="d-flex flex-wrap gap-2 align-items-center mb-1">
                                            <strong>{{ $activity->user?->name ?? 'System' }}</strong>
                                            <span>{{ $activity->description }}</span>
                                        </div>

                                        @if (! empty($activity->metadata['comment']))
                                            <p class="mb-1 text-break">{{ $activity->metadata['comment'] }}</p>
                                        @endif

                                        @if (! empty($activity->metadata['filename']))
                                            <p class="mb-1"><span class="text-muted">File:</span> {{ $activity->metadata['filename'] }}</p>
                                        @endif

                                        <small class="text-muted">{{ $activity->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-4 order-1 order-xl-2">
            <section class="card app-surface mb-4" aria-labelledby="summary-heading">
                <div class="card-body p-3 p-md-4">
                    <h2 id="summary-heading" class="h5 mb-3">Ticket summary</h2>

                    <dl class="ticket-summary mb-0">
                        <dt>Requester</dt>
                        <dd>{{ $ticket->requester->name }}</dd>

                        <dt>Reviewer</dt>
                        <dd>{{ $ticket->reviewer?->name ?? 'Not assigned' }}</dd>

                        <dt>Service item</dt>
                        <dd>{{ $ticket->serviceItem->name }}</dd>

                        <dt>Status</dt>
                        <dd>@include('partials.tickets.status-badge', ['status' => $ticket->status])</dd>

                        <dt>Priority</dt>
                        <dd>@include('partials.tickets.priority-badge', ['priority' => $ticket->priority])</dd>

                        <dt>Created</dt>
                        <dd>{{ $ticket->created_at->format('d.m.Y H:i') }}</dd>

                        <dt>Last updated</dt>
                        <dd>{{ $ticket->updated_at->format('d.m.Y H:i') }}</dd>
                    </dl>
                </div>
            </section>

            @if ($canManageStatus)
                <section class="card app-surface mb-4" aria-labelledby="update-status-heading">
                    <div class="card-body p-3 p-md-4">
                        <h2 id="update-status-heading" class="h6 mb-3">Update status</h2>

                        <form method="POST" action="{{ route('tickets.status.update', $ticket) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select form-select-sm @error('status') is-invalid @enderror" required>
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $ticket->status) === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-sm btn-outline-primary">Update status</button>
                        </form>
                    </div>
                </section>
            @endif

            @if ($canReviewTicket)
                <section class="card app-surface mb-4" aria-labelledby="review-heading">
                    <div class="card-body p-3 p-md-4">
                        <h2 id="review-heading" class="h6 mb-3">Review decision</h2>

                        <form method="POST" action="{{ route('tickets.approval.store', $ticket) }}">
                            @csrf

                            <div class="mb-3">
                                <label for="decision" class="form-label">Decision</label>
                                <select id="decision" name="decision" class="form-select form-select-sm @error('decision') is-invalid @enderror" required>
                                    <option value="">Select decision</option>
                                    <option value="approved" @selected(old('decision') === 'approved')>Approve</option>
                                    <option value="rejected" @selected(old('decision') === 'rejected')>Reject</option>
                                    <option value="more_information_required" @selected(old('decision') === 'more_information_required')>Request more information</option>
                                </select>
                                @error('decision')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="approval_comment" class="form-label">Review comment</label>
                                <textarea
                                    id="approval_comment"
                                    name="comment"
                                    rows="4"
                                    class="form-control form-control-sm @error('comment') is-invalid @enderror"
                                >{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary">Save decision</button>
                        </form>
                    </div>
                </section>
            @endif

            @if ($ticket->approvals->isNotEmpty())
                <section class="card app-surface" aria-labelledby="approval-history-heading">
                    <div class="card-body p-3 p-md-4">
                        <h2 id="approval-history-heading" class="h6 mb-3">Approval history</h2>

                        <div class="d-flex flex-column gap-3">
                            @foreach ($ticket->approvals as $approval)
                                <article class="approval-item">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                        <strong>{{ $approval->reviewer->name }}</strong>
                                        <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $approval->decision)) }}</span>
                                    </div>

                                    @if ($approval->comment)
                                        <p class="mb-1 text-break">{{ $approval->comment }}</p>
                                    @endif

                                    <small class="text-muted">{{ $approval->created_at->format('d.m.Y H:i') }}</small>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
