@extends('layouts.app')

@section('title', 'Create Request - RequestHub')

@section('content')
    @include('partials.page-header', [
        'title' => 'Create Request',
        'subtitle' => 'Submit a new service request for review and tracking.',
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Tickets', 'url' => route('tickets.index', ['view' => 'mine'])],
            ['label' => 'Create Request', 'url' => route('tickets.create'), 'current' => true],
        ],
    ])

    <section class="card app-surface" aria-labelledby="create-request-title">
        <div class="card-body p-3 p-md-4">
            <h2 id="create-request-title" class="h5 mb-4">Request form</h2>

            @if ($serviceItems->isEmpty())
                <div class="alert alert-warning mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle me-1" aria-hidden="true"></i>
                    No active service items are available. Please contact an administrator before creating a request.
                </div>
            @endif

            <form method="POST" action="{{ route('tickets.store') }}" novalidate>
                @csrf

                <fieldset class="mb-4 ticket-create-section">
                    <legend class="h6 mb-3">
                        <i class="bi bi-grid me-1" aria-hidden="true"></i>
                        1. Request type
                    </legend>

                    <div class="col-12 col-lg-7">
                        <label for="service_item_id" class="form-label">Service item</label>
                        <select
                            id="service_item_id"
                            name="service_item_id"
                            class="form-select @error('service_item_id') is-invalid @enderror"
                            @disabled($serviceItems->isEmpty())
                            required
                        >
                            <option value="">Select service item</option>

                            @foreach ($serviceItems as $serviceItem)
                                <option
                                    value="{{ $serviceItem->id }}"
                                    @selected(old('service_item_id') == $serviceItem->id)
                                >
                                    {{ $serviceItem->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Choose the service category that best matches this request.</div>
                        @error('service_item_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>

                <fieldset class="mb-4 ticket-create-section">
                    <legend class="h6 mb-3">
                        <i class="bi bi-card-text me-1" aria-hidden="true"></i>
                        2. Ticket details
                    </legend>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="title" class="form-label">Title</label>
                            <input
                                id="title"
                                type="text"
                                name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}"
                                maxlength="255"
                                required
                            >
                            <div class="form-text">Use a short, descriptive summary.</div>
                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                class="form-control @error('description') is-invalid @enderror"
                                required
                            >{{ old('description') }}</textarea>
                            <div class="form-text">Provide enough context for reviewers to process the request.</div>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mb-4 ticket-create-section">
                    <legend class="h6 mb-3">
                        <i class="bi bi-people me-1" aria-hidden="true"></i>
                        3. Reviewer and priority
                    </legend>

                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label for="reviewer_id" class="form-label">Reviewer (optional)</label>
                            <select
                                id="reviewer_id"
                                name="reviewer_id"
                                class="form-select @error('reviewer_id') is-invalid @enderror"
                            >
                                <option value="">No reviewer selected</option>

                                @foreach ($reviewers as $reviewer)
                                    <option
                                        value="{{ $reviewer->id }}"
                                        @selected(old('reviewer_id') == $reviewer->id)
                                    >
                                        {{ $reviewer->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Leave blank to submit without assigning a reviewer.</div>
                            @error('reviewer_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="priority" class="form-label">Priority</label>
                            <select
                                id="priority"
                                name="priority"
                                class="form-select @error('priority') is-invalid @enderror"
                                required
                            >
                                <option value="low" @selected(old('priority') === 'low')>Low</option>
                                <option value="normal" @selected(old('priority', 'normal') === 'normal')>Normal</option>
                                <option value="high" @selected(old('priority') === 'high')>High</option>
                                <option value="critical" @selected(old('priority') === 'critical')>Critical</option>
                            </select>
                            <div class="form-text">Set urgency based on impact and timeline.</div>
                            @error('priority')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary" @disabled($serviceItems->isEmpty())>
                        <i class="bi bi-send me-1" aria-hidden="true"></i>
                        Submit Request
                    </button>

                    <a href="{{ route('tickets.index', ['view' => 'mine']) }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </section>
@endsection
