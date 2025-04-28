@extends('layouts.master')

@section('title', 'Forgot Password')

@section('content')
<div class="container">
    <h2>Forgot Password</h2>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Send Temporary Password</button>
    </form>
</div>
@endsection
