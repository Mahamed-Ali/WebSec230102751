<!-- @extends('layouts.master')
@section('title', 'products')
@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Product Catalog</h2>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}" style="height: 400px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product['name'] }}</h5>
                        <p class="card-text text-muted">${{ number_format($product['price'], 2) }}</p>
                        <p class="card-text">{{ $product['description'] }}</p>
                        <div class="mt-auto">
                            <button class="btn btn-primary w-100">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
        
@endsection -->