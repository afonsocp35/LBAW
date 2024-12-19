@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    <h2>Your Shopping Cart</h2>
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if($cartItems->isEmpty())
        <p>Your cart is empty!</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Platform</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Added On</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $cartItem)
                    <tr>
                        <td>
                            <a href="{{ url('/product/' . $cartItem['product_id']) }}" 
                               style="text-decoration: none; color: inherit;">
                               {{ $cartItem['productName'] }}
                            </a>
                        </td>
                        <td>{{ implode(', ', $cartItem['platforms']) }}</td> 
                        <td style="text-align: center;">
                            <form action="{{ route('shopping-cart.update', ['id' => Auth::id()]) }}" method="POST" class="update-quantity-form">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                <input type="hidden" name="product_id" value="{{ $cartItem['product_id'] }}">
                                <select name="quantity" class="quantity-dropdown">
                                    @php
                                        // Calculate the maximum value for the dropdown
                                        $maxQuantity = min(10, $cartItem['stock']); 
                                    @endphp
                                    @for($i = 1; $i <= $maxQuantity; $i++)
                                        <option value="{{ $i }}" {{ $cartItem['quantity'] == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </form>
                        </td>
                        <td>${{ number_format($cartItem['price'], 2) }}</td>
                        <td>${{ number_format($cartItem['subtotal'], 2) }}</td>
                        <td>{{ $cartItem['addedOn'] }}</td>
                        <td>
                            <a href="{{ url('/product/' . $cartItem['product_id']) }}">
                                @if($cartItem['image'])
                                    <img src="{{ asset($cartItem['image']) }}" 
                                         alt="{{ $cartItem['productName'] }} Image" 
                                         style="max-width: 100px;">
                                @else
                                    <span>No image available</span>
                                @endif
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('shopping-cart.remove', ['id' => Auth::id()]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $cartItem['product_id'] }}">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                             </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-cost">
            <h3>Total Cost: ${{ number_format($totalCost, 2) }}</h3>
        </div>

        <div class="checkout-button" style="margin-top: 20px;">
            <a href="{{ route('checkout.show') }}" class="btn btn-primary btn-lg">Proceed to Checkout</a>
        </div>
    @endif
</div>
@endsection
