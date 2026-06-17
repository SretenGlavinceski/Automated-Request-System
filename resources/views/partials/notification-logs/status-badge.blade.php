@php
    $statusLabel = ucfirst($status);

    $statusClass = match ($status) {
        'queued' => 'badge-log-status-queued',
        'processing' => 'badge-log-status-processing',
        'sent' => 'badge-log-status-sent',
        'failed' => 'badge-log-status-failed',
        default => 'badge-soft-muted',
    };
@endphp

<span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>

