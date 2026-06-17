@extends('layouts.app')

@section('title', 'Create Service Item - RequestHub')

@section('content')
    @include('partials.page-header', [
        'title' => 'Create Service Item',
        'subtitle' => 'Add a new request category for ticket submissions.',
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Service Items', 'url' => route('service-items.index')],
            ['label' => 'Create', 'url' => route('service-items.create'), 'current' => true],
        ],
    ])

    <section class="card app-surface" aria-labelledby="create-service-item-title">
        <div class="card-body p-3 p-md-4">
            <h2 id="create-service-item-title" class="h5 mb-3">Service item details</h2>

            <form method="POST" action="{{ route('service-items.store') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="form-control @error('description') is-invalid @enderror"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-4">
                    <input
                        id="is_active"
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="form-check-input"
                        @checked(old('is_active', true))
                    >
                    <label for="is_active" class="form-check-label">Active</label>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                        Create
                    </button>
                    <a href="{{ route('service-items.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
