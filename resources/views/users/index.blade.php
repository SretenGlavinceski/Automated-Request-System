@extends('layouts.app')

@section('title', 'Users - RequestHub')

@section('content')
    @include('partials.page-header', [
        'title' => 'Users',
        'subtitle' => 'Manage platform access and role assignments.',
        'breadcrumbs' => [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Users', 'url' => route('users.index'), 'current' => true],
        ],
        'actions' => '<a href="' . route('users.create') . '" class="btn btn-primary btn-sm"><i class="bi bi-person-plus me-1" aria-hidden="true"></i>Create User</a>',
    ])

    @if ($users->isEmpty())
        <section class="card app-surface" aria-labelledby="empty-users-title">
            <div class="card-body p-4 text-center">
                <i class="bi bi-people text-muted fs-3 d-block mb-2" aria-hidden="true"></i>
                <h2 id="empty-users-title" class="h6 mb-1">No users found</h2>
                <p class="text-muted mb-3">Create a user account to assign dashboard access and responsibilities.</p>
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">Create user</a>
            </div>
        </section>
    @else
        <section class="card app-surface" aria-labelledby="users-table-title">
            <div class="card-body p-0">
                <h2 id="users-table-title" class="visually-hidden">User list</h2>

                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 management-table">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>@include('partials.users.role-badge', ['role' => $user->role])</td>
                                <td class="text-end">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-person-gear me-1" aria-hidden="true"></i>
                                        Edit role
                                    </a>
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
