@php
    $priorityLabel = ucfirst($priority);

    $priorityClass = match ($priority) {
        'critical' => 'priority-danger',
        'high' => 'priority-warning',
        'low' => 'priority-muted',
        default => 'priority-info',
    };
@endphp

<span class="badge ticket-badge {{ $priorityClass }}">
    {{ $priorityLabel }}
</span>

