@extends('layouts.app')

@section('title', 'Notification Rules - RequestHub')

@section('content')
    @include('partials.page-header', [
        'title' => 'Notification Rules',
        'subtitle' => 'Configure delivery conditions and channels for ticket events.',
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Notification Rules', 'url' => route('notification-rules.index'), 'current' => true],
        ],
        'actions' => '<a href="' . route('notification-rules.create') . '" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Create Rule</a>',
    ])

    @if ($notificationRules->isEmpty())
        <section class="card app-surface" aria-labelledby="empty-rules-title">
            <div class="card-body p-4 text-center">
                <i class="bi bi-bell text-muted fs-3 d-block mb-2" aria-hidden="true"></i>
                <h2 id="empty-rules-title" class="h6 mb-1">No notification rules created yet</h2>
                <p class="text-muted mb-3">Create a rule to deliver updates for ticket lifecycle events.</p>
                <a href="{{ route('notification-rules.create') }}" class="btn btn-sm btn-primary">Create notification rule</a>
            </div>
        </section>
    @else
        <section class="card app-surface" aria-labelledby="notification-rules-table-title">
            <div class="card-body p-0">
                <h2 id="notification-rules-table-title" class="visually-hidden">Notification rules list</h2>

                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 management-table">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Service item</th>
                            <th scope="col">Event</th>
                            <th scope="col">Recipient</th>
                            <th scope="col">Channels</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($notificationRules as $rule)
                            <tr>
                                <td>{{ $rule->serviceItem?->name ?? 'All service items' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $rule->event)) }}</td>
                                <td>{{ ucfirst($rule->recipient_type) }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if ($rule->send_database)
                                            <span class="badge badge-channel-database">Database</span>
                                        @else
                                            <span class="badge badge-soft-muted">Database off</span>
                                        @endif

                                        @if ($rule->send_email)
                                            <span class="badge badge-channel-mail">Mail</span>
                                        @else
                                            <span class="badge badge-soft-muted">Mail off</span>
                                        @endif
                                    </div>
                                </td>
                                <td>@include('partials.notification-rules.active-badge', ['isActive' => $rule->is_active])</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('notification-rules.edit', $rule) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square me-1" aria-hidden="true"></i>
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('notification-rules.destroy', $rule) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger btn-destructive"
                                                onclick="return confirm('Delete this rule?')"
                                            >
                                                <i class="bi bi-trash me-1" aria-hidden="true"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif
@endsection
