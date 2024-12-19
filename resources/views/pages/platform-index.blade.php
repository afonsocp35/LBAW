@extends('layouts.app')

@section('content')
<div class="platform-list-container">
    <h1>Platform List</h1>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('platform.create') }}" class="btn btn-primary">Add New Platform</a>

    <table class="table">
        <thead>
            <tr>
                <th>Platform Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($platforms as $platform)
                <tr>
                    <td>{{ $platform }}</td>
                    <td>
                        <!-- Removing platforms is restricted in PostgreSQL ENUM types -->
                        <form action="{{ route('platform.destroy', $platform) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" disabled>Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
