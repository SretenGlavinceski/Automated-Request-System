@php
    $user = auth()->user();
    $unreadNotificationsCount = $user?->unreadNotifications()->count() ?? 0;
@endphp

<header class="app-topbar sticky-top">
    <nav class="navbar navbar-expand px-3 px-lg-4" aria-label="Top navigation">
        <button
            class="btn btn-outline-secondary d-lg-none me-2"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#appSidebarOffcanvas"
            aria-controls="appSidebarOffcanvas"
            aria-label="Open sidebar navigation"
        >
            <i class="bi bi-list" aria-hidden="true"></i>
        </button>

        <span class="navbar-brand mb-0 h1 fs-6 fw-semibold">RequestHub</span>

        <div class="ms-auto d-flex align-items-center gap-2 gap-md-3">
            <span class="badge text-bg-light border" title="Unread notifications" aria-label="Unread notifications: {{ $unreadNotificationsCount }}">
                <i class="bi bi-bell me-1" aria-hidden="true"></i>
                {{ $unreadNotificationsCount }}
            </span>

            <div class="text-end d-none d-sm-block">
                <div class="fw-semibold lh-sm">{{ $user?->name ?? 'User' }}</div>
                <small class="text-muted">{{ ucfirst($user?->role ?? 'regular') }}</small>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Log out">
                    <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</header>

