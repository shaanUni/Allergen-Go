<div class="list-main">
<div class="list-info">
    <h2>List of Edible Dishes</h2>
    <h3>Restaurant: <span>{{ $restaurant->name }}</span></h3>
</div>

@foreach ($dishes as $dish)
    @php
        $allergens = \App\Services\AllergenService::parse($dish->allergen_string)['allergens'];
    @endphp

    <div class="list-box">
        <div class="list-text">
            <h4 class="dish-name">{{ $dish->dish_name }}</h4>
            <p class="dish-description">{{ Str::limit($dish->description, 25, '...') }}</p>

            <p class="dish-allergens">
                @foreach(array_slice($allergens, 0, 4) as $allergen)
                    {{ $allergen }}@if(!$loop->last), @else... @endif
                @endforeach
            </p>

            <form method="POST" action="{{ route('user.individual', ['id' => $dish->id, 'state' => 1]) }}">
                @csrf
                <button type="submit" class="action-button">View Dish</button>
            </form>
        </div>
    </div>
@endforeach

@if (count($removeables) > 0)
    <div class="removeable-info">
        <h3>⚠️ Attention:</h3>
        <p>
            The dishes below contain allergens you're allergic to, but the chef has marked them as removable.
            If you choose one, please tell the waiter to remove those allergens during preparation.
        </p>
    </div>
@endif

@foreach ($removeables as $dish)
    @php
        $combined = \App\Services\AllergenService::parse($dish->allergen_string)['combined'];
        $allergens = \App\Services\AllergenService::parse($dish->allergen_string)['allergens'];
    @endphp

    <div class="list-box removeable">
        <div class="list-text">
            <h4 class="dish-name">{{ $dish->dish_name }}</h4>
            <p class="dish-description">{{ $dish->description }}</p>

            <ul class="dish-allergens">
                @foreach($allergens as $allergen)
                    <li class="removeable-allergen">
                        {{ $allergen }}
                        @if($combined[$allergen])
                            <span class="removeable-tag">(Removeable)</span>
                        @endif
                    </li>
                @endforeach
            </ul>

            <p class="removeable-warning">⚠️ This dish contains allergens you marked but they are removable.</p>

            <form method="POST" action="{{ route('user.individual', ['id' => $dish->id, 'state' => 0]) }}">
                @csrf
                <button type="submit" class="action-button alert">View Anyway</button>
            </form>
        </div>
    </div>
@endforeach

</div>