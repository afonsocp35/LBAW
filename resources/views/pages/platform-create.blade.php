@extends('layouts.app')

@section('content')
<div class="platform-create-container">
    <h1>Add New Platform</h1>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('platform.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="platform_name">Platform Name:</label>
            <input type="text" name="platform_name" id="platform_name" class="form-control" placeholder="Enter Platform Name (e.g., PC, MacOS)" required>
        </div>

        <button type="submit" class="btn btn-success">Add Platform</button>
    </form>
</div>
@endsection
