@extends('layouts.dummy')

@section('title', $product->_template->name)

@section('cssRef')
    <link href="{{ url('css/product.css') }}" rel="stylesheet">
@endsection

@section('content')
    <main id="product-wrapper">
        <!-- Back Button -->
        <a href="{{ url()->previous() }}" class="back-button">&lt;</a>

        <!-- product images -->
        <section class="product-gallery">
            @foreach ($product->_template->images as $image)
                @if(file_exists(public_path($image->path)))
                    <img src="{{ asset($image->path) }}" alt="Image #{{ $loop->index + 1 }} of {{ $product->_template->name }}">
                @endif
            @endforeach
        </section>

        <!-- product info -->
        <section class="product-info">
            <div class="product-header">
                <h3 class="product-dev">{{ $product->_template->developer }}</h3>
                <h1 class="product-title">{{ $product->_template->name }}</h1>
                <h2 class="product-price">${{ $product->price }}</h2>
                <div class="product-description">
                    <h3>Description</h3>
                    <p>{{ $product->_template->description }}</p>
                </div>
                <div class="product-options">
                    <h3>Buying for</h3>
                    <div class="product-platform">
                        @foreach ($product->platforms as $platform)
                            <img id="{{ $platform->platform_name }}-logo" 
                                 src="{{ asset('images/platforms/' . $platform->platform_name . '-logo.png') }}"  
                                 alt="{{ $platform->platform_name }}">
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="product-actions">
                <label for="quantity-{{ $product->id }}">Quantity:</label>
                <input type="number" id="quantity-{{ $product->id }}" name="quantity" min="1" value="1">
                <button class="add-to-cart" data-product-id="{{ $product->id }}">Add To Cart</button>
                @if(Auth::check() && Auth::user()->is_admin)
                    <form action="{{ route('product.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="remove-button">Remove Product</button>
                    </form>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn-edit">Edit</a>
                @endif
            </div>
        </section>
    </main>
@endsection
