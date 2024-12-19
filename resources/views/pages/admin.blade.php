@extends('layouts.app')
@section('title', 'Admin')
@section('content')
<div class="user-table-container">
    <div class="actions">
        <a href="{{ route('product.create') }}" class="btn btn-success">Add New Product</a>
        <a href="{{ route('product.index') }}" class="btn btn-success">View All Products</a>
        <a href="{{ route('platform.create') }}" class="btn btn-secondary">Add New Platform</a> 
    </div>
    <h1>All Users</h1>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Seller</th>
                <th>Selling Products</th>
                <th>Actions</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="clickable-row" data-href="{{ url('/profile/' . $user->id) }}">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}{{ $user->id == Auth::user()->id ? " (You)" : ""}}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                    <td>{{ $user->isSeller() ? 'Yes' : 'No' }}</td>
                    <td>{{ $user->countProducts() }}</td>
                    <td>
                        <div class="header-buttons" style="display: flex; justify-content: flex-end; padding: 10px;">
                            
                            <a href="{{ route('purchase.history.show', $user->id) }}" class="header-buttons">
                                <img src="{{ asset('images/history-icon.png') }}" alt="Purchase History" style="width: 50px; height: 50px;">
                            </a>
                        </div>
                    </td>
                    <td>
                        @if(!$user->is_admin && $user->state != 'Banned')
                            <form action="{{ route('admin.destroy', $user->id) }}" method="POST" onsubmit="return confirmDeletion(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        @elseif($user->state == 'Banned')
                            <span>Already Removed</span>
                        @else
                            <span>Cannot Remove</span>
                        @endif
                    </td>
                    <td>
                        @if($user->state === 'Active' && $user->id != Auth::user()->id)
                        <form action="{{ route('users.block', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Block</button>
                        </form>
                        @elseif($user->state === 'Blocked' && $user->id != Auth::user()->id)
                        <form action="{{ route('users.unblock', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Unblock</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="chart-container">
    <h2>Products Sold by Users</h2>
    <canvas id="productsChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chartData = @json($chartData);

        const labels = chartData.map(data => data.name);
        const productCounts = chartData.map(data => data.product_count);

        // Initialize the Chart.js chart
        initializeChart('productsChart', labels, productCounts, 'pie');

        // Make table rows clickable for profile
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', (event) => {
                // Prevent the default redirect behavior only when it's not a delete form submission
                if (!event.target.closest('form')) {
                    const href = row.getAttribute('data-href');
                    window.location.href = href;
                }
            });
        });
    });

    // Function to confirm deletion before proceeding
    function confirmDeletion(event) {
        if (!confirm('Are you sure you want to delete this user?')) {
            event.preventDefault();  // Prevent the form submission if user cancels
        }
    }
</script>
@endsection
