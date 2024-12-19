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

            <!-- Mostrar mensagens globais de erro -->
            @if ($errors->any())
                <div class="error-summary">
                    <h3>There were some problems with your submission:</h3>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/checkout" method="POST"> <!-- todo -->
                @csrf

                <h3>Personal information</h3>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <h3>Payment Details</h3>
                <div class="payment-methods">
                    <label for="payment-method">Select Payment Method</label>
                    <select id="payment-method" name="payment-method" required>
                        <option value="Card" {{ old('payment-method') === 'card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="Paypal" {{ old('payment-method') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                        <option value="Mbway" {{ old('payment-method') === 'mbway' ? 'selected' : '' }}>MBWay</option>
                    </select>
                    @error('payment-method')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <label for="card-number">Card Number</label>
                <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456" value="{{ old('card-number') }}" required>
                @error('card-number')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <label for="expiry-date">Expiry Date</label>
                <input type="month" id="expiry-date" name="expiry-date" placeholder="MM/YY" value="{{ old('expiry-date') }}" required>
                @error('expiry-date')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" placeholder="123" value="{{ old('cvv') }}" required>
                @error('cvv')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <button type="submit">Place Order</button>
            </form>
        </section>
    </main>
@endsection
