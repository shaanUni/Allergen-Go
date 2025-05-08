<h2>List, User!</h2>
    {{ $restaurant->name }}
    <br>

    @foreach ($dishes as $dish)

     --------------------------------------------------------------------------------------------------------------------------
     <br>

        {{ $dish->dish_name }}
        <br>
        {{ $dish->description }}
        <br>
        @php
            $allergens = \App\Services\AllergenService::parse($dish->allergen_string)['allergens'];
        @endphp

        @foreach($allergens as $allergen)
               {{$allergen}} 
        @endforeach

        <br>
    @endforeach

    @foreach ($removeables as $dish)
    --------------------------------------------------------------------------------------------------------------------------
    <br>

    
    @php
            $combined = \App\Services\AllergenService::parse($dish->allergen_string)['combined'];
            $allergens = \App\Services\AllergenService::parse($dish->allergen_string)['allergens'];
        @endphp


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

    --------------------------------------------------------------------------------------------------------------------------

    @endforeach