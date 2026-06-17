@extends('layouts.app')

@section('title', 'Create User - RequestHub')

@section('content')
    @include('partials.page-header', [
        'title' => 'Create User',
        'subtitle' => 'Create a new account and assign an initial role.',
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Users', 'url' => route('users.index')],
            ['label' => 'Create', 'url' => route('users.create'), 'current' => true],
        ],
    ])

    <section class="card app-surface" aria-labelledby="create-user-title">
        <div class="card-body p-3 p-md-4">
            <h2 id="create-user-title" class="h5 mb-3">User details</h2>

            <form method="POST" action="{{ route('users.store') }}" novalidate>
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-md-6">
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

                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm password</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="regular" @selected(old('role') === 'regular')>Regular</option>
                            <option value="reviewer" @selected(old('role') === 'reviewer')>Reviewer</option>
                            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-check me-1" aria-hidden="true"></i>
                        Create user
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
