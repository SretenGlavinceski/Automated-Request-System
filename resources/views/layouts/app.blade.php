<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RequestHub')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<a href="#main-content" class="visually-hidden-focusable">Skip to main content</a>

<div class="app-shell d-flex">
    <aside class="app-sidebar d-none d-lg-flex flex-column p-3" aria-label="Primary sidebar navigation">
        @include('partials.sidebar')
    </aside>

    <div
        class="offcanvas offcanvas-start app-sidebar-mobile"
        tabindex="-1"
        id="appSidebarOffcanvas"
        aria-labelledby="appSidebarOffcanvasLabel"
    >
        <div class="offcanvas-header border-bottom border-light border-opacity-25">
            <h2 class="offcanvas-title fs-5" id="appSidebarOffcanvasLabel">RequestHub</h2>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close sidebar"></button>
        </div>

        <div class="offcanvas-body p-3">
            @include('partials.sidebar', ['isMobile' => true])
        </div>
    </div>

    <div class="app-main flex-grow-1 d-flex flex-column">
        @include('partials.topbar')

        <main id="main-content" class="app-content container-fluid py-4">
            @include('partials.flash-messages')
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>

