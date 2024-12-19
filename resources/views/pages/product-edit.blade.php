@extends('layouts.app')

@section('content')
<div class="edit-product-container">
    <h1>Edit Product</h1>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form action="{{ route('product.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="{{ $product->_template->name }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="name">Product Developer:</label>
            <input type="text" id="developer" name="developer" value="{{ $product->_template->developer }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Product Description:</label>
            <textarea id="description" name="description" class="form-control" rows="10" required>{{ $product->_template->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="{{ $product->price }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" value="{{ $product->stock }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="seller_id">Seller:</label>
            <select id="seller_id" name="seller_id" class="form-control">
                @foreach($sellers as $seller)
                    <option value="{{ $seller->id }}" {{ $product->_seller->id == $seller->id ? 'selected' : '' }}>
                        {{ $seller->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Save Changes</button>
    </form>
</div>
@endsection
