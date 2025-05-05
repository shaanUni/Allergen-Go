@extends('admin.layout')

@section('content')
    <h1>Welcome to the Admin Dashboard</h1>
    <p>This is your secure area.</p>
    <a href="{{ route('admin.dishes.index') }}">Dishes</a>
@endsection
