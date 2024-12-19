@extends('layouts.dummy')

@section('title', "Checkout at Clubhouse")

@section('cssRef')
    <link href="{{ url('css/checkout.css') }}" rel="stylesheet">
@endsection

@section('content')
    
    <main class="checkout-wrapper">
    <a href="{{ route('shopping-cart.show') }}" class="back-button">&lt; </a>

        <section class="order-summary">
            <h2>Order Summary</h2>
            <table>
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->_product->_template->name  }}</td>
                        <td>{{ $item->_product->price }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->_product->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td colspan="3">Total</td>
                    <td>${{ $total }}</td>
                </tr>
                </tbody>
            </table>
        </section>

        <section class="billing-details">
            <h2>Billing Information</h2>
            <form action="/checkout" method="POST"> <!-- todo -->
                @csrf

                <h3>Personal information</h3>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}" required>

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}" required>

                <h3>Payment Details</h3>
                <div class="payment-methods">
                    <label for="payment-method">Select Payment Method</label>
                    <select id="payment-method" name="payment-method" required>
                        <option value="credit-card">Credit Card</option>
                        <!-- <option value="paypal">PayPal</option>
                        <option value="mbway">MBWay</option> -->
                    </select>
                </div>

                <label for="card-number">Card Number</label>
                <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456" required>

                <label for="expiry-date">Expiry Date</label>
                <input type="month" id="expiry-date" name="expiry-date" placeholder="MM/YY" required>

                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" placeholder="123" required>

                <!-- todo -->
                <!--
                <label for="paypal-email">Expiry Date</label>
                <input type="email" id="paypal-email" name="paypal-email" value="{{ $user->email }}" required>

                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone">
                 -->

                <button type="submit">Place Order</button>
            </form>
        </section>
    </main>
@endsection
