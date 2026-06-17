@php
    $user = auth()->user();
    $currentView = request('view', 'mine');

    $isMobile = $isMobile ?? false;

    $isDashboard = request()->routeIs('dashboard');
    $isCreateTicket = request()->routeIs('tickets.create');
    $isMyTickets = request()->routeIs('tickets.index') && $currentView === 'mine';
    $isReviewTickets = request()->routeIs('tickets.index') && $currentView === 'review';
    $isAllTickets = request()->routeIs('tickets.index') && $currentView === 'all';
    $isServiceItems = request()->routeIs('service-items.*');
    $isUsers = request()->routeIs('users.*');
    $isNotificationRules = request()->routeIs('notification-rules.*');
    $isAuditLogs = request()->routeIs('audit-logs.*');
    $isNotificationLogs = request()->routeIs('notification-logs.*');
@endphp

<div class="d-flex flex-column h-100">
    @if (! $isMobile)
        <div class="mb-4">
            <a href="{{ route('dashboard') }}" class="text-white text-decoration-none fw-semibold fs-5">
                RequestHub
            </a>
            <p class="text-white-50 mb-0 small">Service request management</p>
        </div>
    @endif

    <nav class="flex-grow-1" aria-label="Main navigation">
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a
                    href="{{ route('dashboard') }}"
                    class="nav-link {{ $isDashboard ? 'active' : '' }}"
                    @if ($isMobile) data-bs-dismiss="offcanvas" @endif
                    @if ($isDashboard) aria-current="page" @endif
                >
                    <i class="bi bi-speedometer2 me-2" aria-hidden="true"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a
                    href="{{ route('tickets.create') }}"
                    class="nav-link {{ $isCreateTicket ? 'active' : '' }}"
                    @if ($isMobile) data-bs-dismiss="offcanvas" @endif
                    @if ($isCreateTicket) aria-current="page" @endif
                >
                    <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>
                    Create Request
                </a>
            </li>

            <li class="nav-item">
                <a
                    href="{{ route('tickets.index', ['view' => 'mine']) }}"
                    class="nav-link {{ $isMyTickets ? 'active' : '' }}"
                    @if ($isMobile) data-bs-dismiss="offcanvas" @endif
                    @if ($isMyTickets) aria-current="page" @endif
                >
                    <i class="bi bi-ticket-detailed me-2" aria-hidden="true"></i>
                    My Tickets
                </a>
            </li>

            @if ($user->canReviewTickets())
                <li class="nav-item">
                    <a
                        href="{{ route('tickets.index', ['view' => 'review']) }}"
                        class="nav-link {{ $isReviewTickets ? 'active' : '' }}"
                        @if ($isMobile) data-bs-dismiss="offcanvas" @endif
                        @if ($isReviewTickets) aria-current="page" @endif
                    >
                        <i class="bi bi-check2-square me-2" aria-hidden="true"></i>
                        Tickets for Review
                    </a>
                </li>
            @endif

            @if ($user->isAdmin())
                <li class="mt-3 mb-1 px-2 text-white-50 text-uppercase small">Administration</li>

                <li class="nav-item">
                    <a
                        href="{{ route('tickets.index', ['view' => 'all']) }}"
                        class="nav-link {{ $isAllTickets ? 'active' : '' }}"
                        @if ($isMobile) data-bs-dismiss="offcanvas" @endif
                        @if ($isAllTickets) aria-current="page" @endif
                    >
                        <i class="bi bi-collection me-2" aria-hidden="true"></i>
                        All Tickets
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="{{ route('service-items.index') }}"
                        class="nav-link {{ $isServiceItems ? 'active' : '' }}"
                        @if ($isMobile) data-bs-dismiss="offcanvas" @endif
                        @if ($isServiceItems) aria-current="page" @endif
                    >
                        <i class="bi bi-grid me-2" aria-hidden="true"></i>
                        Service Items
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="{{ route('users.index') }}"
                        class="nav-link {{ $isUsers ? 'active' : '' }}"
                        @if ($isMobile) data-bs-dismiss="offcanvas" @endif
                        @if ($isUsers) aria-current="page" @endif
                    >
                        <i class="bi bi-people me-2" aria-hidden="true"></i>
                        Users
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="{{ route('notification-rules.index') }}"
                        class="nav-link {{ $isNotificationRules ? 'active' : '' }}"
                        @if ($isMobile) data-bs-dismiss="offcanvas" @endif
                        @if ($isNotificationRules) aria-current="page" @endif
                    >
                        <i class="bi bi-bell me-2" aria-hidden="true"></i>
                        Notification Rules
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="{{ route('audit-logs.index') }}"
                        class="nav-link {{ $isAuditLogs ? 'active' : '' }}"
                        @if ($isMobile) data-bs-dismiss="offcanvas" @endif
                        @if ($isAuditLogs) aria-current="page" @endif
                    >
                        <i class="bi bi-journal-text me-2" aria-hidden="true"></i>
                        Audit Logs
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="{{ route('notification-logs.index') }}"
                        class="nav-link {{ $isNotificationLogs ? 'active' : '' }}"
                        @if ($isMobile) data-bs-dismiss="offcanvas" @endif
                        @if ($isNotificationLogs) aria-current="page" @endif
                    >
                        <i class="bi bi-envelope-check me-2" aria-hidden="true"></i>
                        Notification Logs
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>

