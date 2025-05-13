@extends('admin.layout')

@section('content')
<div class="admin-form">
    <h2>Create New Dish</h2>

    <form action="{{ route('admin.dishes.store') }}" method="POST">
        @include('admin.dishes.form')
    </form>
</div>

@endsection
