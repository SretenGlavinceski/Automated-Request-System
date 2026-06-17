@extends('layouts.guest')

@section('title', 'Login - RequestHub')

@section('content')
    <section aria-labelledby="login-heading" class="guest-auth-section">
        <h2 id="login-heading" class="h5 mb-1 text-center">Sign in</h2>
        <p class="text-muted text-center small mb-4">Use your account to access RequestHub.</p>

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

        <form method="POST" action="/login" novalidate>
            @csrf

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
                        autocomplete="current-password"
                        required
                    >
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-4">
                <input
                    id="remember"
                    class="form-check-input"
                    type="checkbox"
                    name="remember"
                    @checked(old('remember'))
                >
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 guest-submit-btn">
                <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
                Login
            </button>
        </form>

        <p class="text-center mt-4 mb-0 small">
            Do not have an account?
            <a href="/register" class="fw-semibold">Register</a>
        </p>
    </section>
@endsection
