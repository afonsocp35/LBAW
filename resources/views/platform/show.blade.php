@extends('layouts.app')
@section('title', $platform)
@section('content')
    <header>
        <h1>{{ $platform ? "Games for $platform" : 'All Games' }}</h1>
    </header>

    <main>
        <div class="game-catalog">
            @forelse($games as $name => $group)
                @foreach ($group as $game)
                    <div class="game-card">
                        <img src="{{ $game['image'] ? asset($game['image']) : asset('images/placeholder.jpg') }}" alt="{{ $game['name'] }}">
                        <h2>{{ $game['name'] }}</h2>
                        <p>${{ number_format($game['price'], 2) }}</p>
                        <a href="{{ route('product.show', ['id' => $game['id']]) }}" class="button">View Details</a>
                    </div>
                @endforeach
            @empty
                <p>No games available {{ $platform ? "for $platform" : '' }}.</p>
            @endforelse
        </div>
    </main>
@endsection
