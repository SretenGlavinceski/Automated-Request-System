@extends('layouts.guest')

@section('title', 'Register - RequestHub')

@section('content')
    <section aria-labelledby="register-heading" class="guest-auth-section">
        <h2 id="register-heading" class="h5 mb-1 text-center">Create account</h2>
        <p class="text-muted text-center small mb-4">Create your profile to start submitting requests.</p>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <p class="fw-semibold mb-2">Please correct the following errors:</p>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register" novalidate>
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Full name</label>
                <div class="input-group">
                    <span class="input-group-text" aria-hidden="true">
                        <i class="bi bi-person"></i>
                    </span>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        required
                    >
                </div>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <div class="input-group">
                    <span class="input-group-text" aria-hidden="true">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                    >
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text" aria-hidden="true">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        autocomplete="new-password"
                        required
                    >
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirm password</label>
                <div class="input-group">
                    <span class="input-group-text" aria-hidden="true">
                        <i class="bi bi-shield-check"></i>
                    </span>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        autocomplete="new-password"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 guest-submit-btn">
                <i class="bi bi-person-plus me-1" aria-hidden="true"></i>
                Register
            </button>
        </form>

        <p class="text-center mt-4 mb-0 small">
            Already have an account?
            <a href="/login" class="fw-semibold">Login</a>
        </p>
    </section>
@endsection
