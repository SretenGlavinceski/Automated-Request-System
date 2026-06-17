@php
    $statusLabel = ucfirst(str_replace('_', ' ', $status));

    $statusClass = match ($status) {
        'approved', 'completed', 'closed' => 'status-success',
        'rejected' => 'status-danger',
        'more_information_required', 'in_review', 'in_progress' => 'status-warning',
        default => 'status-info',
    };
@endphp

<span class="badge ticket-badge {{ $statusClass }}">
    {{ $statusLabel }}
</span>

