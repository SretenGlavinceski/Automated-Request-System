@extends('layouts.app')

@section('title', 'Dashboard - RequestHub')

@section('content')
    @php
        $currentUser = auth()->user();
        $dashboardSubtitle = sprintf(
            'Welcome, %s (%s)',
            $currentUser->name,
            ucfirst($currentUser->role)
        );
    @endphp

    @include('partials.page-header', [
        'title' => 'Dashboard',
        'subtitle' => $dashboardSubtitle,
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard'), 'current' => true],
        ],
        'actions' => '<a href="' . route('tickets.create') . '" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Create Request</a>',
    ])

    <section class="mb-4" aria-labelledby="overview-heading">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 id="overview-heading" class="h5 mb-0">Overview</h2>
        </div>

        <div class="row g-3">
            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                <article class="card dashboard-metric metric-info h-100">
                    <div class="card-body d-flex align-items-start justify-content-between">
                        <div>
                            <p class="summary-label mb-1">My tickets</p>
                            <p class="summary-value mb-0">{{ $myTicketsCount }}</p>
                        </div>
                        <span class="metric-icon" aria-hidden="true">
                            <i class="bi bi-ticket-perforated"></i>
                        </span>
                    </div>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                <article class="card dashboard-metric metric-success h-100">
                    <div class="card-body d-flex align-items-start justify-content-between">
                        <div>
                            <p class="summary-label mb-1">My open tickets</p>
                            <p class="summary-value mb-0">{{ $myOpenTicketsCount }}</p>
                        </div>
                        <span class="metric-icon" aria-hidden="true">
                            <i class="bi bi-folder-check"></i>
                        </span>
                    </div>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                <article class="card dashboard-metric metric-info h-100">
                    <div class="card-body d-flex align-items-start justify-content-between">
                        <div>
                            <p class="summary-label mb-1">Unread notifications</p>
                            <p class="summary-value mb-0">{{ $unreadNotificationsCount }}</p>
                        </div>
                        <span class="metric-icon" aria-hidden="true">
                            <i class="bi bi-bell"></i>
                        </span>
                    </div>
                </article>
            </div>

            @if (auth()->user()->canReviewTickets())
                <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                    <article class="card dashboard-metric metric-info h-100">
                        <div class="card-body d-flex align-items-start justify-content-between">
                            <div>
                                <p class="summary-label mb-1">Tickets for review</p>
                                <p class="summary-value mb-0">{{ $reviewTicketsCount }}</p>
                            </div>
                            <span class="metric-icon" aria-hidden="true">
                                <i class="bi bi-clipboard-check"></i>
                            </span>
                        </div>
                    </article>
                </div>

                <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                    <article class="card dashboard-metric metric-warning h-100">
                        <div class="card-body d-flex align-items-start justify-content-between">
                            <div>
                                <p class="summary-label mb-1">Pending reviews</p>
                                <p class="summary-value mb-0">{{ $pendingReviewCount }}</p>
                            </div>
                            <span class="metric-icon" aria-hidden="true">
                                <i class="bi bi-hourglass-split"></i>
                            </span>
                        </div>
                    </article>
                </div>
            @endif

            @if (auth()->user()->isAdmin())
                <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                    <article class="card dashboard-metric metric-info h-100">
                        <div class="card-body d-flex align-items-start justify-content-between">
                            <div>
                                <p class="summary-label mb-1">All tickets</p>
                                <p class="summary-value mb-0">{{ $allTicketsCount }}</p>
                            </div>
                            <span class="metric-icon" aria-hidden="true">
                                <i class="bi bi-collection"></i>
                            </span>
                        </div>
                    </article>
                </div>

                <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                    <article class="card dashboard-metric metric-danger h-100">
                        <div class="card-body d-flex align-items-start justify-content-between">
                            <div>
                                <p class="summary-label mb-1">Failed notifications</p>
                                <p class="summary-value mb-0">{{ $failedNotificationsCount }}</p>
                            </div>
                            <span class="metric-icon" aria-hidden="true">
                                <i class="bi bi-exclamation-octagon"></i>
                            </span>
                        </div>
                    </article>
                </div>
            @endif
        </div>
    </section>

    <section class="mb-4" aria-labelledby="quick-actions-heading">
        <div class="card app-surface">
            <div class="card-body p-3">
                <h2 id="quick-actions-heading" class="h5 mb-3">Quick actions</h2>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
                        Create request
                    </a>
                    <a href="{{ route('tickets.index', ['view' => 'mine']) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-ticket-detailed me-1" aria-hidden="true"></i>
                        My tickets
                    </a>

                    @if (auth()->user()->canReviewTickets())
                        <a href="{{ route('tickets.index', ['view' => 'review']) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-check2-square me-1" aria-hidden="true"></i>
                            Tickets for review
                        </a>
                    @endif

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('tickets.index', ['view' => 'all']) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-collection me-1" aria-hidden="true"></i>
                            All tickets
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4" aria-labelledby="notifications-heading" id="unread-notifications">
        <div class="card app-surface">
            <div class="card-body p-3">
                <h2 id="notifications-heading" class="h5 mb-3">Unread notifications</h2>

                @if (auth()->user()->unreadNotifications->isEmpty())
                    <p class="text-muted mb-0">No unread notifications.</p>
                @else
                    <div class="list-group list-group-flush dashboard-feed">
                        @foreach (auth()->user()->unreadNotifications as $notification)
                            <article class="list-group-item px-0 py-3">
                                <p class="mb-2">{{ $notification->data['message'] }}</p>

                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Open ticket</button>
                                    </form>

                                    <small class="text-muted">
                                        {{ $notification->created_at->format('d.m.Y H:i') }}
                                    </small>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if (auth()->user()->isAdmin())
        <section aria-labelledby="audit-heading">
            <div class="card app-surface">
                <div class="card-body p-3">
                    <h2 id="audit-heading" class="h5 mb-3">Recent audit activity</h2>

                    @if ($recentAuditLogs->isEmpty())
                        <p class="text-muted mb-0">No recent audit activity.</p>
                    @else
                        <div class="list-group list-group-flush dashboard-feed">
                            @foreach ($recentAuditLogs as $log)
                                <article class="list-group-item px-0 py-3">
                                    <div class="fw-semibold">{{ $log->user?->name ?? 'System' }}</div>
                                    <div>{{ $log->description }}</div>
                                    <small class="text-muted">{{ $log->created_at->format('d.m.Y H:i') }}</small>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif
@endsection
