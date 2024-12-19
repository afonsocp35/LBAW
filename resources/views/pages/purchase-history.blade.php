@extends('layouts.app')

@section('title', 'Purchase History')

@section('content')
<div class="container">
    <h2>Your Purchase History</h2>

    @if($orders->isEmpty())
        <p>You have no purchase history yet.</p>
    @else
        @foreach ($orders as $order)
            <div class="order mb-4">
                <h3>Order #{{ $order->id }}</h3>
                <p>Placed on: {{ $order->ordered_on }}</p>
                <p>Status: {{ ucfirst($order->status) }}</p>
                <p>Shipping Address: {{ $order->shipping_address ?? 'No address provided' }}</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <!-- Validação para evitar acessar objetos null -->
                                <td>{{ $item->_product->_template->name ?? 'Unknown Product' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-end">
                    <strong>Total:</strong> ${{ number_format($order->items->sum(fn($item) => $item->quantity * $item->price), 2) }}
                </div>
            </div>
            <hr>
        @endforeach
    @endif
</div>
@endsection