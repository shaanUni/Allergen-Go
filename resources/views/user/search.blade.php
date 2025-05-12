@extends('user.layout')

@section('content')
    <div class="search-page">

        @if(session('failure'))
            <div class="alert-box alert-danger">
                {{ session('failure') }}
            </div>
        @endif

        <div class="search-section">
            <h1 class="search-title">Search for Dishes</h1>

            <form method="POST" action="{{ route('user.searchCode') }}" class="allergy-form">
                @csrf
                @include('components.form')
            </form>
        </div>

        <div class="info-box">
            <h2>About AllergenGo</h2>
            <p>If you're here, you probably have allergies. The code you enter will identify the restaurant you're eating at.</p>
            <p>Once you select your allergens and submit the form, we'll show you dishes that are safe for you.</p>
        </div>

    </div>
@endsection
