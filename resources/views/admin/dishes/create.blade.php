@extends('admin.layout')

@section('content')
    <h2>Create New Dish</h2>

    <form action="{{ route('admin.dishes.store') }}" method="POST">
        @include('admin.dishes.form')
    </form>
@endsection
