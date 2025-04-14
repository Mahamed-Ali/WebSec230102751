@extends('layouts.master')
@section('title', 'Customers List')
@section('content')

<h3 class="m-3">Customers</h3>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th><th>Email</th><th>Credit</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->credit }} EGP</td>
            <td>
                <a href="{{ route('profile', $customer->id) }}" class="btn btn-sm btn-primary">View</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
