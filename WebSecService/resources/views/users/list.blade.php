@extends('layouts.master')

@section('title', 'Users List')

@section('content')

<h1>Users List</h1>

{{-- Start of Search Form --}}
<form action="{{ route('users_list') }}" method="GET">
    <div class="row mb-2">

        {{-- Search by Name --}}
        <div class="col-md-6">
            <label for="keywords" class="form-label">Search by Name</label>
            <input type="text" name="keywords" class="form-control" placeholder="Enter Name" value="{{ request()->keywords }}">
        </div>

        {{-- Search by ID --}}
        <div class="col-md-6">
            <label for="id_filter" class="form-label">Search by ID</label>
            <select name="id_filter" class="form-select">
                <option value="">Select User ID</option>
                @foreach($allUsers as $u)
                    <option value="{{ $u->id }}" {{ request()->id_filter == $u->id ? 'selected' : '' }}>
                        {{ $u->id }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    <div class="row mb-3">
        {{-- Filter Button --}}
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>

        {{-- Reset Button --}}
        <div class="col-md-2">
            <a href="{{ route('users_list') }}" class="btn btn-danger w-100">Reset</a>
        </div>

        {{-- Add New User Button --}}
        <div class="col-md-8 text-end">
            <a href="{{ route('users_add') }}" class="btn btn-success">Add New User</a>
        </div>
    </div>
</form>
{{-- End of Search Form --}}

{{-- Users Table --}}
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th style="width: 10%;">ID</th>
            <th style="width: 30%;">Name</th>
            <th style="width: 40%;">Email</th>
            <th style="width: 20%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <a href="{{ route('users_edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <a href="{{ route('users_delete', $user->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete {{ $user->name }}?')">Delete</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4">No users found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Pagination Links --}}
{{ $users->links() }}

@endsection
