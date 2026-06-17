@php
    $channelLabel = ucfirst($channel);

    $channelClass = match ($channel) {
        'database' => 'badge-channel-database',
        'mail' => 'badge-channel-mail',
        default => 'badge-soft-muted',
    };
@endphp

<span class="badge {{ $channelClass }}">{{ $channelLabel }}</span>

