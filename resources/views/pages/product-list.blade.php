@extends('layouts.app')
@section('title', 'All Products')
@section('content')
<div class="product-table-container">
    <h1>All Products</h1>

    @if($notifications->isNotEmpty())
        <div class="alert alert-info">
            <strong>You have new notifications:</strong>
            <ul>
                @foreach ($notifications as $notification)
                    <li>{{ $notification->data['product_name'] ?? 'Product Update' }} - {{ $notification->created_at->diffForHumans() }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="product-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Platform</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Seller</th>
                <th>Actions</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
    @foreach ($products as $product)
        <tr class="clickable-row" data-href="{{ url('/product/' . $product->id) }}">
            <td>{{ $product->id }}</td>
            <td>{{ $product->_template->name }}</td>
            <td>
                {{-- Iterate through the platforms collection --}}
                @if($product->platforms->isNotEmpty())
                    {{ $product->platforms->pluck('platform_name')->join(', ') }}
                @else
                    No Platforms
                @endif
            </td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->_seller->name }}</td>
            <td>
                <form action="{{ route('product.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                </form>
            </td>
            <td>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">Edit</a>
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>
</div>

{{-- Add a script to handle clickable rows --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', function () {
                window.location.href = this.dataset.href;
            });
        });
    });
</script>
@endsection
