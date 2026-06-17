@extends('layouts.app')

@section('title', 'Edit User Role - RequestHub')

@section('content')
	@include('partials.page-header', [
		'title' => 'Edit User Role',
		'subtitle' => $user->name . ' (' . $user->email . ')',
		'breadcrumbs' => [
			['label' => 'Dashboard', 'url' => route('dashboard')],
			['label' => 'Users', 'url' => route('users.index')],
			['label' => 'Edit role', 'url' => route('users.edit', $user), 'current' => true],
		],
	])

	<section class="card app-surface" aria-labelledby="edit-user-role-title">
		<div class="card-body p-3 p-md-4">
			<h2 id="edit-user-role-title" class="h5 mb-3">Role assignment</h2>

			<form method="POST" action="{{ route('users.update', $user) }}" novalidate>
				@csrf
				@method('PUT')

				<div class="mb-3">
					<label for="role" class="form-label">Role</label>
					<select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
						<option value="regular" @selected(old('role', $user->role) === 'regular')>Regular</option>
						<option value="reviewer" @selected(old('role', $user->role) === 'reviewer')>Reviewer</option>
						<option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
					</select>
					@error('role')
						<div class="invalid-feedback d-block">{{ $message }}</div>
					@enderror
				</div>

				<div class="d-flex flex-wrap gap-2 mt-4">
					<button type="submit" class="btn btn-primary">
						<i class="bi bi-save me-1" aria-hidden="true"></i>
						Save changes
					</button>
					<a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
				</div>
			</form>
		</div>
	</section>
@endsection

