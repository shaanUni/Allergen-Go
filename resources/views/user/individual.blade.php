@extends('user.layout')

@section('content')
<div class="dish-card {{ $state == 0 ? 'dish-card--warning' : '' }}">
    
    <!-- Header with back button -->
    <div class="dish-card__header">
            <button onclick="history.back()" type="submit" class="icon-button" title="Go Back">Back</button>
    </div>

    <!-- Dish content -->
    <div class="dish-card__content">

        <h2 class="dish-card__title">{{ $dish->dish_name }}</h2>
        <h3 class="dish-card__title">£{{ $dish->price }}</h3>

        <p class="dish-card__description">
            {{ $dish->description }}
        </p>

        <!-- Ingredients list -->
        <div class="dish-card__ingredients">
            <h3>Ingredients</h3>
            <ul>
                @foreach ($allergens as $allergen)
                    <li>
                        <span class="allergen">{{ $allergen }}</span>
                        @if($removeable[$allergen])
                            <span class="tag-removeable">Removeable</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        @if ($state == 0)
        <div class="warning-box">
            <strong>⚠️ This dish contains allergens</strong><br>
            Some allergens can be removed, but please speak to the staff before ordering.
        </div>
        @endif

    </div>
</div>
@endsection
