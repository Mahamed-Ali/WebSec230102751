@extends('layouts.master')
@section('title', 'Mini Test')
@section('content')
<div class="container mt-3">
  <h2>Mini Test</h2>
  
  <table class="table table-dark table-striped">
  <thead class="table-dark">
            <tr>
                <th>no</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price (per unit)</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($bills as $bill => $item)
                @php 
                    $total = $item['quantity'] * $item['price'];
                    $grandTotal += $total;
                @endphp
                <tr>
                    <td>{{ $bill + 1 }}</td>
                    <td>{{ $item['item'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>${{ number_format($total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Grand Total</th>
                <th>${{ number_format($grandTotal, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
</div>
@endsection
