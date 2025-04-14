@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="row">
    <div class="m-4 col-sm-6">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <tr>
                <th>Name</th><td>{{$user->name}}</td>
            </tr>
            <tr>
                <th>Email</th><td>{{$user->email}}</td>
            </tr>
            <tr>
                <th>Roles</th>
                <td>
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{$role->name}}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Credit</th>
                <td>{{$user->credit}} EGP</td>
            </tr>
            <tr>
                <th>Permissions</th>
                <td>
                    @foreach($permissions as $permission)
                        <span class="badge bg-success">{{$permission->display_name}}</span>
                    @endforeach
                </td>
            </tr>
        </table>
        @if(auth()->user()->hasPermissionTo('charge_credit'))
            <form method="POST" action="{{ route('users.charge_credit', $user->id) }}">
                @csrf
                <div class="mb-3">
                    <label for="amount" class="form-label">Add Credit (EGP):</label>
                    <input type="number" name="amount" min="1" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning">Charge Credit</button>
            </form>
        @endif

        @can('charge_credit')
            <form method="POST" action="{{ route('users_charge_credit', $user->id) }}" class="mt-3">
                @csrf
                <div class="mb-2">
                    <label for="amount">Add Credit (EGP)</label>
                    <input type="number" name="amount" class="form-control" required min="1">
                </div>
                <button type="submit" class="btn btn-warning">Charge Credit</button>
            </form>
        @endcan


        @if(auth()->user()->hasPermissionTo('charge_credit'))
            <form action="{{ route('charge_credit', $user->id) }}" method="POST" class="mt-3">
                @csrf
                <div class="input-group">
                    <input type="number" name="amount" class="form-control" placeholder="Amount to Add" min="1" required>
                    <button type="submit" class="btn btn-primary">Add Credit</button>
                </div>
            </form>
        @endif



        <div class="row">
            <div class="col col-6">
            </div>
            @if(auth()->user()->hasPermissionTo('admin_users')||auth()->id()==$user->id)
            <div class="col col-4">
                <a class="btn btn-primary" href='{{route('edit_password', $user->id)}}'>Change Password</a>
            </div>
            @else
            <div class="col col-4">
            </div>
            @endif
            @if(auth()->user()->hasPermissionTo('edit_users')||auth()->id()==$user->id)
            <div class="col col-2">
                <a href="{{route('users_edit', $user->id)}}" class="btn btn-success form-control">Edit</a>
            </div>
            @endif

            @if($user->boughtProducts->count())
                <hr>
                <h4>Purchased Products</h4>
                <ul class="list-group">
                    @foreach($user->boughtProducts as $product)
                        <li class="list-group-item">
                            <strong>{{ $product->name }}</strong> - {{ $product->price }} EGP
                        </li>
                    @endforeach
                </ul>
            @endif

        </div>
    </div>
</div>
@endsection
