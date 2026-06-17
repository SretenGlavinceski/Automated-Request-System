@extends('layouts.app')

@section('title', 'Tickets - RequestHub')

@section('content')
    @php
        $pageTitle = match ($view) {
            'review' => 'Tickets for Review',
            'all' => 'All Tickets',
            default => 'My Tickets',
        };

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
    @endphp

    @include('partials.page-header', [
        'title' => $pageTitle,
        'subtitle' => 'Track requests and monitor status updates.',
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Tickets', 'url' => route('tickets.index', ['view' => $view]), 'current' => true],
        ],
        'actions' => '<a href="' . route('tickets.create') . '" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Create Request</a>',
    ])

    <section class="mb-3" aria-label="Ticket views">
        <ul class="nav nav-tabs tickets-tabs">
            <li class="nav-item">
                <a
                    href="{{ route('tickets.index', ['view' => 'mine']) }}"
                    class="nav-link {{ $view === 'mine' ? 'active' : '' }}"
                    @if ($view === 'mine') aria-current="page" @endif
                >
                    My Tickets
                </a>
            </li>

            @if (auth()->user()->canReviewTickets())
                <li class="nav-item">
                    <a
                        href="{{ route('tickets.index', ['view' => 'review']) }}"
                        class="nav-link {{ $view === 'review' ? 'active' : '' }}"
                        @if ($view === 'review') aria-current="page" @endif
                    >
                        Tickets for Review
                    </a>
                </li>
            @endif

            @if (auth()->user()->isAdmin())
                <li class="nav-item">
                    <a
                        href="{{ route('tickets.index', ['view' => 'all']) }}"
                        class="nav-link {{ $view === 'all' ? 'active' : '' }}"
                        @if ($view === 'all') aria-current="page" @endif
                    >
                        All Tickets
                    </a>
                </li>
            @endif
        </ul>
    </section>

    <section class="card app-surface mb-4" aria-labelledby="filters-heading">
        <div class="card-body p-3">
            <h2 id="filters-heading" class="h6 mb-3">Search and filters</h2>

            <form class="row g-2 g-md-3 align-items-end" method="GET" action="{{ route('tickets.index') }}">
                <input type="hidden" name="view" value="{{ $view }}">

                <div class="col-12 col-md-6 col-lg-5">
                    <label for="search" class="form-label mb-1">Ticket number or title</label>
                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control form-control-sm"
                        placeholder="Search tickets"
                    >
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <label for="status" class="form-label mb-1">Status</label>
                    <select id="status" name="status" class="form-select form-select-sm">
                        <option value="">All statuses</option>

                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2 col-lg-4 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-funnel me-1" aria-hidden="true"></i>
                        Apply
                    </button>

                    <a href="{{ route('tickets.index', ['view' => $view]) }}" class="btn btn-sm btn-outline-secondary">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </section>

    @if ($tickets->isEmpty())
        <section class="card app-surface" aria-labelledby="empty-state-title">
            <div class="card-body p-4 text-center">
                <i class="bi bi-inbox text-muted fs-3 d-block mb-2" aria-hidden="true"></i>
                <h2 id="empty-state-title" class="h6 mb-1">No tickets found</h2>
                <p class="text-muted mb-3">Try adjusting your search terms or status filter.</p>
                <a href="{{ route('tickets.index', ['view' => $view]) }}" class="btn btn-sm btn-outline-primary">Reset filters</a>
            </div>
        </section>
    @else
        <section class="card app-surface" aria-labelledby="tickets-table-title">
            <div class="card-body p-0">
                <h2 id="tickets-table-title" class="visually-hidden">Ticket list</h2>

                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 tickets-table">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Number</th>
                            <th scope="col">Title</th>
                            <th scope="col">Service item</th>
                            <th scope="col">Status</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Requester</th>
                            <th scope="col">Reviewer</th>
                            <th scope="col">Created</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="fw-semibold text-decoration-none">
                                        {{ $ticket->ticket_number }}
                                    </a>
                                </td>
                                <td>{{ $ticket->title }}</td>
                                <td>{{ $ticket->serviceItem->name }}</td>
                                <td>
                                    @include('partials.tickets.status-badge', ['status' => $ticket->status])
                                </td>
                                <td>
                                    @include('partials.tickets.priority-badge', ['priority' => $ticket->priority])
                                </td>
                                <td>{{ $ticket->requester->name }}</td>
                                <td>{{ $ticket->reviewer?->name ?? 'Not assigned' }}</td>
                                <td>
                                    <span class="text-nowrap">{{ $ticket->created_at->format('d.m.Y H:i') }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif
@endsection
