<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $users = User::orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'role' => ['required', 'in:regular,reviewer,admin'],
        ]);

        $oldRole = $user->role;

        $user->update([
            'role' => $validated['role'],
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_role_changed',
            'entity_type' => User::class,
            'entity_id' => $user->id,
            'description' => "Role changed for user {$user->name}.",
            'old_values' => [
                'role' => $oldRole,
            ],
            'new_values' => [
                'role' => $validated['role'],
            ],
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User role updated successfully.');
    }

    public function create(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
            'role' => ['required', 'in:regular,reviewer,admin'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_created',
            'entity_type' => User::class,
            'entity_id' => $user->id,
            'description' => "User {$user->name} was created.",
            'old_values' => null,
            'new_values' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }
}
