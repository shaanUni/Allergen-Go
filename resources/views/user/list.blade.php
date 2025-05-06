@extends('user.layout')

@section('content')
    <h2>List, User!</h2>
    {{ $restaurant->name }}
    <br>

    @foreach ($dishes as $dish)
        {{ $dish->dish_name }}
        <br>
        {{ $dish->description }}
        <br>
        {{ $dish->allergen_string }}
        <br>
    @endforeach
@endsection
