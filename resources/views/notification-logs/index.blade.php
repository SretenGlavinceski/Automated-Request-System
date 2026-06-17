@extends('layouts.app')

@section('title', 'Notification Logs - RequestHub')

@section('content')
    @include('partials.page-header', [
        'title' => 'Notification Logs',
        'subtitle' => 'Review delivery attempts and notification processing outcomes.',
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Notification Logs', 'url' => route('notification-logs.index'), 'current' => true],
        ],
    ])

    @if ($notificationLogs->isEmpty())
        <section class="card app-surface" aria-labelledby="empty-notification-logs-title">
            <div class="card-body p-4 text-center">
                <i class="bi bi-envelope-check text-muted fs-3 d-block mb-2" aria-hidden="true"></i>
                <h2 id="empty-notification-logs-title" class="h6 mb-1">No notification logs found</h2>
                <p class="text-muted mb-0">Delivery records will appear here after notifications are dispatched.</p>
            </div>
        </section>
    @else
        <section class="card app-surface" aria-labelledby="notification-logs-table-title">
            <div class="card-body p-0">
                <h2 id="notification-logs-table-title" class="visually-hidden">Notification logs table</h2>

                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 management-table logs-table">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Ticket</th>
                            <th scope="col">Recipient</th>
                            <th scope="col">Channel</th>
                            <th scope="col">Status</th>
                            <th scope="col">Attempts</th>
                            <th scope="col">Message</th>
                            <th scope="col">Error</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($notificationLogs as $log)
                            <tr>
                                <td><span class="text-nowrap">{{ $log->created_at->format('d.m.Y H:i') }}</span></td>
                                <td>
                                    @if ($log->ticket)
                                        <a href="{{ route('tickets.show', $log->ticket) }}" class="text-decoration-none fw-semibold">
                                            {{ $log->ticket->ticket_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">Deleted ticket</span>
                                    @endif
                                </td>
                                <td>{{ $log->recipient->name }}</td>
                                <td>@include('partials.notification-logs.channel-badge', ['channel' => $log->channel])</td>
                                <td>@include('partials.notification-logs.status-badge', ['status' => $log->status])</td>
                                <td>{{ $log->attempts }}</td>
                                <td class="log-message-cell">{{ $log->message }}</td>
                                <td class="log-message-cell">{{ $log->error_message ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif
@endsection
