@extends('layouts.app')

@section('title', 'Edit Notification Rule - RequestHub')

@section('content')
    @include('partials.page-header', [
        'title' => 'Edit Notification Rule',
        'subtitle' => ucfirst(str_replace('_', ' ', $notificationRule->event)) . ' for ' . ucfirst($notificationRule->recipient_type),
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Notification Rules', 'url' => route('notification-rules.index')],
            ['label' => 'Edit', 'url' => route('notification-rules.edit', $notificationRule), 'current' => true],
        ],
    ])

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <section class="card app-surface" aria-labelledby="edit-rule-title">
                <div class="card-body p-3 p-md-4">
                    <h2 id="edit-rule-title" class="h5 mb-3">Rule details</h2>

                    <form method="POST" action="{{ route('notification-rules.update', $notificationRule) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="service_item_id" class="form-label">Service item</label>
                                <select id="service_item_id" name="service_item_id" class="form-select @error('service_item_id') is-invalid @enderror">
                                    <option value="">All service items</option>

                                    @foreach ($serviceItems as $serviceItem)
                                        <option
                                            value="{{ $serviceItem->id }}"
                                            @selected(old('service_item_id', $notificationRule->service_item_id) == $serviceItem->id)
                                        >
                                            {{ $serviceItem->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_item_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="event" class="form-label">Event</label>
                                <select id="event" name="event" class="form-select @error('event') is-invalid @enderror" required>
                                    @foreach ([
                                        'ticket_created' => 'Ticket created',
                                        'ticket_approved' => 'Ticket approved',
                                        'ticket_rejected' => 'Ticket rejected',
                                        'more_information_required' => 'More information required',
                                        'comment_added' => 'Comment added',
                                    ] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('event', $notificationRule->event) === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('event')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="recipient_type" class="form-label">Recipient</label>
                                <select id="recipient_type" name="recipient_type" class="form-select @error('recipient_type') is-invalid @enderror" required>
                                    <option value="requester" @selected(old('recipient_type', $notificationRule->recipient_type) === 'requester')>Requester</option>
                                    <option value="reviewer" @selected(old('recipient_type', $notificationRule->recipient_type) === 'reviewer')>Reviewer</option>
                                </select>
                                @error('recipient_type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="subject" class="form-label">Email subject</label>
                                <input
                                    id="subject"
                                    type="text"
                                    name="subject"
                                    class="form-control @error('subject') is-invalid @enderror"
                                    value="{{ old('subject', $notificationRule->subject) }}"
                                >
                                @error('subject')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label">Message template</label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="5"
                                    class="form-control @error('message') is-invalid @enderror"
                                    required
                                >{{ old('message', $notificationRule->message) }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input
                                        id="send_database"
                                        type="checkbox"
                                        name="send_database"
                                        value="1"
                                        class="form-check-input"
                                        @checked(old('send_database', $notificationRule->send_database))
                                    >
                                    <label for="send_database" class="form-check-label">Send in-app notification</label>
                                </div>

                                <div class="form-check">
                                    <input
                                        id="send_email"
                                        type="checkbox"
                                        name="send_email"
                                        value="1"
                                        class="form-check-input"
                                        @checked(old('send_email', $notificationRule->send_email))
                                    >
                                    <label for="send_email" class="form-check-label">Send email</label>
                                </div>

                                <div class="form-check">
                                    <input
                                        id="is_active"
                                        type="checkbox"
                                        name="is_active"
                                        value="1"
                                        class="form-check-input"
                                        @checked(old('is_active', $notificationRule->is_active))
                                    >
                                    <label for="is_active" class="form-check-label">Active</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1" aria-hidden="true"></i>
                                Save changes
                            </button>
                            <a href="{{ route('notification-rules.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        <div class="col-12 col-lg-4">
            @include('partials.notification-rules.template-variables')
        </div>
    </div>
@endsection
