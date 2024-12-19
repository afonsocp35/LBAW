@extends('layouts.app')

@section('title', "Profile")

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Profile Picture and User Info -->
        <div class="col-md-4 text-center">
            <img src="{{ asset($user->profile_picture ?? 'images/default-profile.png') }}" 
                 alt="Profile Picture" 
                 class="img-fluid rounded-circle mb-3" 
                 style="width: 150px; height: 150px;">
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->username }}</p>
            @if(Auth::id() == $user->id || Auth::user()->is_admin)
                <a class="btn btn-primary" href="{{ route('profile.edit', ['id' => $user->id]) }}">Edit Profile</a>
            @endif
        </div>

        <!-- Profile Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Profile Details</h4>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Username:</strong> {{ $user->username }}</p>
                    <p><strong>Address:</strong> {{ $user->address ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Remove Account Button -->
        <div class="col-12 mt-3">
            @if(Auth::user()->is_admin && !$user->is_admin && Auth::id() != $user->id && $user->state != 'Banned')
                <!-- Admin can remove non-admin users -->
                <form action="{{ route('admin.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Remove User</button>
                </form>
            @elseif(Auth::id() == $user->id && !$user->is_admin && $user->state != 'Banned')
                <!-- Non-admin users can remove their own account -->
                <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Remove My Account</button>
                </form>
            @elseif($user->state == 'Banned')
                <span class="text-muted">Banned Account.</span>
            @else
                <!-- No option to remove if the conditions aren't met -->
                <span class="text-muted">This account cannot be removed.</span>
            @endif
        </div>
    </div>
</div>
@endsection
