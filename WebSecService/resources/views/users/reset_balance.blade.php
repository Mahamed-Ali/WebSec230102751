@extends('layouts.master')
@section('title', 'Add Credit')
@section('content')
<div class="container">
    <h1>Add Credit to {{ $user->name }}</h1>
    <form action="{{ route('users.resetBalance', [$user->id]) }}" method="POST" style="display:inline-block;">
        @csrf
            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to reset this balance?')">Reset Balance</button>
    </form>
</div>
@endsection
        

        