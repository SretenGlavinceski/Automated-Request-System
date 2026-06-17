@php
    $title = $title ?? '';
    $subtitle = $subtitle ?? null;
    $breadcrumbs = $breadcrumbs ?? [];
    $actions = $actions ?? null;
@endphp

<header class="mb-4">
    @if (! empty($breadcrumbs))
        <nav aria-label="Breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 small">
                @foreach ($breadcrumbs as $breadcrumb)
                    @php
                        $isCurrent = $loop->last || ! empty($breadcrumb['current']);
                    @endphp

                    <li class="breadcrumb-item {{ $isCurrent ? 'active' : '' }}" @if ($isCurrent) aria-current="page" @endif>
                        @if (! $isCurrent && ! empty($breadcrumb['url']))
                            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                        @else
                            {{ $breadcrumb['label'] }}
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-between gap-3">
        <div>
            <h1 class="h3 mb-1">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-muted mb-0">{{ $subtitle }}</p>
            @endif
        </div>

        @if ($actions)
            <div class="ms-md-3">
                {!! $actions !!}
            </div>
        @endif
    </div>
</header>

