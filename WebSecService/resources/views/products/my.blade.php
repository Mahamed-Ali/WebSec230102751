@extends('layouts.master')
@section('title', 'My Purchased Products')
@section('content')

<h1>My Products</h1>

@foreach($products as $product)
    <div class="card mt-2">
        <div class="card-body">
            <h5>{{$product->name}}</h5>
            <p>Model: {{$product->model}}</p>
            <p>Code: {{$product->code}}</p>
            <p>Price: {{$product->price}} EGP</p>
        </div>
    </div>
@endforeach

@endsection
