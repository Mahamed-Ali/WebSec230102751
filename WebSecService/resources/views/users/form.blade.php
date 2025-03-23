@extends('layouts.master')

@section('title', $user->id ? 'Edit User' : 'Add User')

@section('content')

<h1>{{ $user->id ? 'Edit' : 'Add' }} User</h1>

<form action="{{ $user->id ? route('users_save', $user->id) : route('users_save') }}" method="POST">
    @csrf

    @if($user->id)
    <div class="mb-3">
        <label for="id" class="form-label">User ID</label>
        <input type="text" class="form-control" value="{{ $user->id }}" disabled>
    </div>
    @endif

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password {{ $user->id ? '(Leave blank to keep current password)' : '' }}</label>
        <input type="password" name="password" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">{{ $user->id ? 'Update' : 'Save' }}</button>
</form>

@endsection
