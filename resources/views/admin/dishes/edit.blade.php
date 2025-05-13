@extends('admin.layout')

@section(section: 'content')
    <div class="admin-form">
        <h2>Edit Dish</h2>

        <form action="{{ route('admin.dishes.update', $dish) }}" method="POST">
            @method('PUT')
            @include('admin.dishes.form')
        </form>

    </div>
@endsection