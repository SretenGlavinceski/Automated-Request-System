@extends('layouts.app')

@section('title', 'Audit Logs - RequestHub')

@section('content')
    @include('partials.page-header', [
        'title' => 'Audit Logs',
        'subtitle' => 'Track administrative actions and entity-level changes.',
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Audit Logs', 'url' => route('audit-logs.index'), 'current' => true],
        ],
    ])

    @if ($auditLogs->isEmpty())
        <section class="card app-surface" aria-labelledby="empty-audit-title">
            <div class="card-body p-4 text-center">
                <i class="bi bi-journal-text text-muted fs-3 d-block mb-2" aria-hidden="true"></i>
                <h2 id="empty-audit-title" class="h6 mb-1">No audit logs found</h2>
                <p class="text-muted mb-0">Audit entries will appear here when tracked actions occur.</p>
            </div>
        </section>
    @else
        <section class="card app-surface" aria-labelledby="audit-table-title">
            <div class="card-body p-0">
                <h2 id="audit-table-title" class="visually-hidden">Audit logs table</h2>

                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 management-table logs-table">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">User</th>
                            <th scope="col">Action</th>
                            <th scope="col">Description</th>
                            <th scope="col">Entity</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($auditLogs as $log)
                            <tr>
                                <td><span class="text-nowrap">{{ $log->created_at->format('d.m.Y H:i') }}</span></td>
                                <td>{{ $log->user?->name ?? 'System' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $log->action)) }}</td>
                                <td class="log-message-cell">{{ $log->description }}</td>
                                <td>
                                    {{ class_basename($log->entity_type) }}
                                    @if ($log->entity_id)
                                        #{{ $log->entity_id }}
                                    @endif
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
