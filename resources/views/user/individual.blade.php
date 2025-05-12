@extends('user.layout')

@section('content')
<div class="dish-card {{ $state == 0 ? 'dish-card--warning' : '' }}">
    <!-- Top controls -->
    <div class="dish-card__header">
        <button class="icon-button">&larr;</button>
    </div>

    <!-- Dish image -->
    <div class="dish-card__image">
        <!-- You can put your image tag here -->
        {{-- <img src="{{ asset('path/to/image.jpg') }}" alt="Dish Image"> --}}
    </div>

    <!-- Dish info -->
    <div class="dish-card__content">

        <h2 class="dish-card__title">{{ $dish->dish_name }}</h2>
        <p class="dish-card__description">{{ $dish->description }}</p>

        <!-- Ingredients -->
        <div class="dish-card__ingredients">
            <h3>INGREDIENTS</h3>
            <ul>
                @foreach ($allergens as $allergen)
                    <li>
                        {{ $allergen }}
                        @if($removeable[$allergen])
                            <span class="removeable">(Removeable)</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</div>
@endsection
