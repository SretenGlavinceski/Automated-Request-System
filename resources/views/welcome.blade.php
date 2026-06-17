@extends('layouts.guest')

@section('title', 'RequestHub')

@section('content')
    <section class="text-center" aria-labelledby="welcome-title">
        <h2 id="welcome-title" class="h5 mb-2">Welcome</h2>
        <p class="text-muted mb-4">
            Centralized service-request workflows for regular users, reviewers, and administrators.
        </p>

        @if (Route::has('login'))
            <div class="d-flex flex-wrap justify-content-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-speedometer2 me-1" aria-hidden="true"></i>
                        Open Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </section>
@endsection
