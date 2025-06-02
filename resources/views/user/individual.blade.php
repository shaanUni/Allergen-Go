@extends('user.layout')

@section('content')
    <div class="dish-card {{ $state == 0 ? 'dish-card--warning' : '' }}">

        <!-- Header with back button -->
        <div class="dish-card__header">
            <button onclick="history.back()" type="submit" class="icon-button" title="Go Back">Back</button>
        </div>

        <!-- Dish content -->
        <div class="dish-card__content {{ $state == 0 ? 'danger' : 'nish'}}">

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


            @php
                $selectedBool = false;
                $sessionKey = 'selectedRemoveableDishes'. $uuid;
                if($state == 1){
                    $sessionKey = 'selectedDishes'. $uuid;
                }
                if (session( $sessionKey)) {
                    $dishArray = session( $sessionKey);
                    if (in_array($dish->id, $dishArray)) {
                        $selectedBool = true;
                    }
                }
            @endphp
            <div class="list-form">
            <form method="POST" action="{{ route('user.adddish', ['id' => $dish->id, 'state' => $state]) }}">
                @csrf
                <input type="hidden" name="uuid" value="{{ $uuid }}">

                <button type="submit" class="action-button-select {{ $selectedBool ? 'remove-button' : ''}}">{{ $selectedBool ? 'Remove Dish' : 'Add dish'}}</button>
            </form>
            </div>
        </div>
    </div>
@endsection