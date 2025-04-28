@extends('layouts.master')

@section('title', 'Change Password')

@section('content')
<div class="container">
    <h2>Change Password</h2>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Change Password</button>
    </form>
</div>
@endsection
