@php
    $roleLabel = ucfirst($role);

    $roleClass = match ($role) {
        'admin' => 'badge-role-admin',
        'reviewer' => 'badge-role-reviewer',
        default => 'badge-role-regular',
    };
@endphp

<span class="badge {{ $roleClass }}">{{ $roleLabel }}</span>

