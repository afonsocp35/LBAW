@extends('layouts.app')
@section('title', 'Clubhouse')
@section('content')
    <header>
        <h1>Game Catalog</h1>
    </header>
    <main>
        <div class="game-catalog">
            @foreach($games as $gameGroup)
                @php
                    $game = $gameGroup->first(); // Get the first game in the group (they all have the same name)
                @endphp
                <!-- Wrap each game card with an anchor tag to make it clickable -->
                    <div class="game-card">
                        <img src="{{ $game['image'] ? asset($game['image']) : asset('images/placeholder.jpg') }}" alt="{{ $game['name'] }}">
                        <h2>{{ $game['name'] }}</h2>
                        <p>${{ number_format($game['price'], 2) }}</p>
                        <a href="{{ route('product.show', ['id' => $game['id']]) }}" class="button">View Details</a>
                    </div>
                </a>
            @endforeach
        </div>
    </main>
@endsection
