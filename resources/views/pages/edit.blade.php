@extends('layouts.app')

@section('title', "Editar Perfil")

@section('content')
<div class="container mt-5">
    <form action="{{ route('profile.update', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="{{ $user->username }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="address">Endereço:</label>
            <input type="text" name="address" id="address" value="{{ $user->address }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="profile_picture">Foto de Perfil:</label>
            <input type="file" name="profile_picture" id="profile_picture" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
@endsection
