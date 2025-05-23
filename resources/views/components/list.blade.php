<div class="list-main">

    <div class="list-forms top">
        <form method="GET" action="{{ route('user.qr', ['code' => $restaurant->restaurant_code]) }}">
            @csrf
            <button type="submit" class="top-buttons">Re-select Allergens</button>
        </form>
        @if (Route::currentRouteName() == 'user.selected')
            <form method="POST" action="{{ route('user.reset') }}">
                @csrf
                <button type="submit" class="top-buttons right-btn">Re-select dishes</button>
            </form>
        @endif

    </div>

    <div class="list-info">
        <h2>{{ Route::currentRouteName() == 'user.selected' ? 'List of Selected Dishes' : ' List of Edible Dishes' }}
        </h2>
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
                    @foreach (array_slice($allergens, 0, 4) as $allergen)
                        {{ $allergen }}@if (!$loop->last)
                        , @else...
                        @endif
                    @endforeach
                </p>

                <div class="list-forms">
                    <form method="POST" action="{{ route('user.individual', ['id' => $dish->id, 'state' => 1]) }}">
                        @csrf
                        <button type="submit" class="action-button">View Dish</button>
                    </form>
                    @php
                        $selectedBool = false;
                        if (session('selectedDishes')) {
                            $dishArray = session('selectedDishes');
                            if (in_array($dish->id, $dishArray)) {
                                $selectedBool = true;
                            }
                        }
                    @endphp
                    @if (Route::currentRouteName() != 'user.selected')
                        <form method="POST" action="{{ route('user.adddish', ['id' => $dish->id, 'state' => 1]) }}">
                            @csrf
                            <button type="submit"
                                class="action-button add-button {{ $selectedBool ? 'remove-button' : '' }}">{{ $selectedBool ? 'Remove Dish' : 'Add dish' }}</button>
                        </form>
                    @endif

                </div>
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
                    @foreach ($allergens as $allergen)
                        <li class="removeable-allergen">
                            {{ $allergen }}
                            @if ($combined[$allergen])
                                <span class="removeable-tag">(Removeable)</span>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <p class="removeable-warning">⚠️ This dish contains allergens you marked but they are removable.</p>
                <div class="list-forms">

                    <form method="POST" action="{{ route('user.individual', ['id' => $dish->id, 'state' => 0]) }}">
                        @csrf
                        <button type="submit" class="action-button alert">View</button>
                    </form>
                    @php
                        $selectedBool = false;
                        if (session('selectedRemoveableDishes')) {
                            $removeableDishArray = session('selectedRemoveableDishes');
                            if (in_array($dish->id, $removeableDishArray)) {
                                $selectedBool = true;
                            }
                        }
                    @endphp
                    @if (Route::currentRouteName() != 'user.selected')
                        <form method="POST" action="{{ route('user.adddish', ['id' => $dish->id, 'state' => 0]) }}">
                            @csrf
                            <button type="submit"
                                class="action-button add-button {{ $selectedBool ? 'remove-button' : '' }}">{{ $selectedBool ? 'Remove Dish' : 'Add dish' }}</button>
                        </form>
                    @endif

                </div>
            </div>
    @endforeach



</div>
@if (Route::currentRouteName() != 'user.selected')
    <div class="list-text finished-btn">

        <form method="POST" action="{{ route('user.selected') }}">
            @csrf
            <button type="submit" class="action-button ">Finished</button>
        </form>
    </div>
@endif

</div>
