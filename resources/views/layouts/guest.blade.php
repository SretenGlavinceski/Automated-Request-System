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

<main id="main-content" class="guest-shell">
    <div class="guest-panel">
        <div class="guest-card p-4 p-md-4">
            <div class="guest-brand text-center mb-4">
                <div class="guest-brand-icon mx-auto mb-2" aria-hidden="true">
                    <i class="bi bi-building-gear"></i>
                </div>
                <h1 class="h4 mb-1">RequestHub</h1>
                <p class="text-muted mb-0">Enterprise request management</p>
            </div>

            @yield('content')
        </div>
    </div>
</main>
</body>
</html>

