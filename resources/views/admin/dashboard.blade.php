@extends('admin.layout')

@section('content')
    <h1>Welcome to the Admin Dashboard</h1>
    <p>This is your secure area.</p>
    <a href="{{ route('admin.dishes.index') }}">Dishes</a>
    <a href="{{ route('admin.qrcode') }}">QR code</a>
    <a href="{{ route('admin.stats') }}">Stats</a>



    @if ($restaurant_code == null)
    <p>You need to generate a new code for users to use the app.</p>
    <form action="{{ route('admin.generate') }}" method="GET">
    @csrf
    <button type="submit" class="btn btn-primary">Generate</button>
</form>

    @else
        <p>Your restaurants unique code for users is:</p>
        {{ $restaurant_code }}
    @endif

    @endsection
