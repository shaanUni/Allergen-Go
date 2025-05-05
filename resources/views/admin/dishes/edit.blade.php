@extends('admin.layout')

@section(section: 'content')
    <h2>Edit Dish</h2>

    <form action="{{ route('admin.dishes.update', $dish) }}" method="POST">
        @method('PUT')
        @include('admin.dishes.form')
    </form>
@endsection
