@extends('layouts.app')

@section('title', 'Service Items - RequestHub')

@section('content')
    @include('partials.page-header', [
        'title' => 'Service Items',
        'subtitle' => 'Manage request categories available to users.',
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Service Items', 'url' => route('service-items.index'), 'current' => true],
        ],
        'actions' => '<a href="' . route('service-items.create') . '" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Create Service Item</a>',
    ])

    @if ($serviceItems->isEmpty())
        <section class="card app-surface" aria-labelledby="empty-service-items-title">
            <div class="card-body p-4 text-center">
                <i class="bi bi-grid text-muted fs-3 d-block mb-2" aria-hidden="true"></i>
                <h2 id="empty-service-items-title" class="h6 mb-1">No service items created yet</h2>
                <p class="text-muted mb-3">Create your first service item to organize incoming requests.</p>
                <a href="{{ route('service-items.create') }}" class="btn btn-sm btn-primary">Create service item</a>
            </div>
        </section>
    @else
        <section class="card app-surface" aria-labelledby="service-items-table-title">
            <div class="card-body p-0">
                <h2 id="service-items-table-title" class="visually-hidden">Service items list</h2>

                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 management-table">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($serviceItems as $serviceItem)
                            <tr>
                                <td class="fw-semibold">{{ $serviceItem->name }}</td>
                                <td>{{ $serviceItem->description ?? 'No description' }}</td>
                                <td>@include('partials.service-items.active-badge', ['isActive' => $serviceItem->is_active])</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('service-items.edit', $serviceItem) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square me-1" aria-hidden="true"></i>
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('service-items.destroy', $serviceItem) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger btn-destructive"
                                                onclick="return confirm('Delete this service item?')"
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
