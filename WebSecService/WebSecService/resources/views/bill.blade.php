@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center">Supermarket Bill</h2>

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price (per unit)</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp

                @foreach($items as $index => $item)
                    @php
                        $total = $item['quantity'] * $item['price'];
                        $grandTotal += $total;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>${{ number_format($item['price'], 2) }}</td>
                        <td>${{ number_format($total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-success">
                    <td colspan="4" class="text-start"><strong>Grand Total:</strong></td>
                    <td><strong>${{ number_format($grandTotal, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
