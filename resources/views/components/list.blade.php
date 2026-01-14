<div class="list-main">

    <div class="list-forms top-actions top">
        <form method="GET" action="{{ \Illuminate\Support\Str::contains(Route::currentRouteName(), 'user') ? route('user.qr', ['code' => $restaurant->restaurant_code]) : route('admin.stats') }}">
            @csrf
            <button type="submit" class="top-buttons">Re-select Allergens</button>
        </form>
        @if (Route::currentRouteName() == 'user.selected')
            <form method="POST" action="{{ route('user.reset') }}">
                @csrf
                <input type="hidden" name="uuid" value="{{ $uuid }}">
                <button type="submit" class="top-buttons ">Re-select dishes</button>
            </form>
        @endif
    </div>

    <div class="list-info">
        <h2>
            {{ Route::currentRouteName() == 'user.selected'
                ? 'List of Selected Dishes'
                : 'List of Edible Dishes' }}
        </h2>
        <h3>Restaurant: <span>{{ $restaurant->name }}</span></h3>
        <p>We hope allergengo has been useful in helping you choose a suitable meal to eat! If you liked using allergengo
           make sure you tell other restaurants about us, so you, and many other people with allergies can eat safe wherever you are!
        </p>
    </div>
    <p>Your dishes, safe to eat:
        </p>
        <br>
    @foreach ($dishes as $dish)
        @php
            $allergens = \App\Services\AllergenService::parse($dish->allergen_string)['allergens'];
        @endphp

     
        
        <div class="list-box">
            <div class="list-text">
                <h4 class="dish-name">Dish Name: {{ $dish->dish_name }}</h4>
                <div class="stat-list">
                    @php
                        $halal = $dish->halal     ? 'halal,'     : '';
                        $vegan = $dish->vegan     ? 'vegan,'     : '';
                        $vegetarian = $dish->vegetarian ? 'vegetarian,' : '';
                    @endphp
                    <p class="stat-item">
                        <strong>{{ $halal }} {{ $vegan }} {{ $vegetarian }}</strong>
                    </p>
                </div>

                <p class="dish-allergens">
                    @if ($dish->no_allergens)
                        This dish contains no allergens!
                    @else
                        @foreach (array_slice($allergens, 0, 4) as $allergen)
                            {{ $allergen }}@if (! $loop->last), @else...@endif
                        @endforeach
                    @endif
                </p>

                <div class="list-forms">
                    <form method="POST" action="{{ Route::currentRouteName() == 'admin.search' ? route('admin.individual', ['id' => $dish->id, 'state' => 1]) : route('user.individual', ['id' => $dish->id, 'state' => 1]) }}">
                        @csrf
                        <input type="hidden" name="uuid" value="{{ $uuid }}">
                        <button type="submit" class="action-button">View Dish</button>
                    </form>

                    @php
                        $selectedBool = session('selectedDishes'.$uuid, []);
                    @endphp
                    @if (Route::currentRouteName() != 'user.selected' && Route::currentRouteName() != 'admin.search')
                        <form method="POST" action="{{ route('user.adddish', ['id' => $dish->id, 'state' => 1]) }}">
                            @csrf
                            <input type="hidden" name="uuid" value="{{ $uuid }}">
                            <button type="submit"
                                class="action-button add-button {{ in_array($dish->id, $selectedBool) ? 'remove-button' : '' }}">
                                {{ in_array($dish->id, $selectedBool) ? 'Remove Dish' : 'Add dish' }}
                            </button>
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
                If you choose one, please tell the waiter to remove those allergens during preparation. Do this at your own risk
                -  we can not guarantee the restaurant can do this safeley. Speak to the restaurant for more details.
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
                <div class="stat-list">
                    @php
                        $halal = $dish->halal     ? 'halal,'     : '';
                        $vegan = $dish->vegan     ? 'vegan,'     : '';
                        $vegetarian = $dish->vegetarian ? 'vegetarian,' : '';
                    @endphp
                    <p class="stat-item">
                        <strong>{{ $halal }} {{ $vegan }} {{ $vegetarian }}</strong>
                    </p>
                </div>
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

                <p class="removeable-warning">
                    ⚠️ This dish contains allergens you marked but they are removable.
                </p>

                <div class="list-forms">
                    <form method="POST" action="{{ route('user.individual', ['id' => $dish->id, 'state' => 0]) }}">
                        @csrf
                        <input type="hidden" name="uuid" value="{{ $uuid }}">
                        <button type="submit" class="action-button alert">View</button>
                    </form>

                    @php
                        $selectedBool = session('selectedRemoveableDishes'.$uuid, []);
                    @endphp
                    @if (Route::currentRouteName() != 'user.selected' && Route::currentRouteName() != 'admin.search')
                        <form method="POST" action="{{ route('user.adddish', ['id' => $dish->id, 'state' => 0]) }}">
                            @csrf
                            <input type="hidden" name="uuid" value="{{ $uuid }}">
                            <button type="submit"
                                class="action-button add-button {{ in_array($dish->id, $selectedBool) ? 'remove-button' : '' }}">
                                {{ in_array($dish->id, $selectedBool) ? 'Remove Dish' : 'Add dish' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div> {{-- ← added this closing tag --}}
    @endforeach

    @if (Route::currentRouteName() != 'user.selected' && Route::currentRouteName() != 'admin.search')
        <div class="list-text finished-btn">
            <form method="POST" action="{{ route('user.selected') }}">
                @csrf
                <input type="hidden" name="uuid" value="{{ $uuid }}">
                <button type="submit" class="action-button">Finished</button>
            </form>
        </div>
    @endif

</div>
