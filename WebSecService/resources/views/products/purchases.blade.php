@extends('layouts.master')
@section('title', 'Purchased Products')
@section('content')

<div class="row mt-4">
    <div class="col">
        <h2>Your Purchased Products</h2>
    </div>
</div>

@if($products->isEmpty())
    <div class="alert alert-info mt-3">You haven't purchased any products yet.</div>
@else
    @foreach($products as $product)
        <div class="card mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-lg-4">
                        <img src="{{asset("images/$product->photo")}}" class="img-thumbnail" alt="{{$product->name}}" width="100%">
                    </div>
                    <div class="col-sm-12 col-lg-8 mt-3">
                        <h4>{{$product->name}}</h4>
                        <table class="table table-striped">
                            <tr><th>Model</th><td>{{$product->model}}</td></tr>
                            <tr><th>Code</th><td>{{$product->code}}</td></tr>
                            <tr><th>Price</th><td>{{$product->price}} EGP</td></tr>
                            <tr><th>Description</th><td>{{$product->description}}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

@endsection
