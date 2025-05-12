<div class="list-info">
    <h2>List of edible Allergens:</h2>
    <h3>Restaurant you are at: {{ $restaurant->name }}</h3>
</div>
<br>

@foreach ($dishes as $dish)

    <br>
    <div class="list-box">
        <div class="list-text">
            {{ $dish->dish_name }}
            <br>

            <p>{{ Str::limit($dish->description, 25, '...') }}</p>
            <br>
            @php
                $allergens = \App\Services\AllergenService::parse($dish->allergen_string)['allergens'];
            @endphp
            @foreach(array_slice($allergens, 0, 4) as $allergen)
                {{ $allergen }}@if($loop->last).. @else , @endif

            @endforeach


            <br>
        </div>
    </div>
@endforeach

@if (count($removeables) > 0)
    <div class="removeable-info">
        <h3>Please read before selecting any dishes Below:</h3>
        <br>
        <p>The dishes below contains allergens that you are allergic to. However, the chef has marked them as removeable,
            meaning they can prepare the dish for you without that allergen. If you do select one of these dishes, Please
            clearly communicate with the chef/waiter it must be removed.
        </p>
    </div>
@endif

@foreach ($removeables as $dish)
    <br>


    @php
        $combined = \App\Services\AllergenService::parse($dish->allergen_string)['combined'];
        $allergens = \App\Services\AllergenService::parse($dish->allergen_string)['allergens'];
    @endphp
    <div class="list-box removeable">
        <div class="list-text">

            {{ $dish->dish_name }}
            <br>
            {{ $dish->description }}
            <br>

            @foreach($allergens as $allergen)
                @if($combined[$allergen])
                    {{$allergen}} Removeable
                @else
                    {{$allergen}}
                @endif
                <br>
            @endforeach

            <br>

            Warning this is a removeable one...
            <br>
        </div>
    </div>

@endforeach