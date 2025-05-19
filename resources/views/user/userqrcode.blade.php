@extends('user.layout')

@section('content')
<div class="search-page">

    @if(session('failure'))
        <div class="alert-box alert-danger">
            {{ session('failure') }}
        </div>
    @endif

    <div class="main">
    <div class="search-section">
            <h1 class="search-title">Search for Dishes</h1>
            <form method="POST" action="{{ route('user.searchCode') }}" class="allergy-form">
                @csrf
                @include('components.form')
            </form>
        </div>
        <p>If you want to be able to manually enter a new code, click <a href="{{ route('user.search') }}">here</a>. Also look below for more information on AllergenGo.</p>

        <div class="info-box">

            <h2>Some information about AllergenGo: </h2>
        <p>If you are here, you must have allergies. The restaurant code you will enter will identify where you are eating. </p>
        <br>    
        <p>Once you add your allergies then submit alongside with the code, we will return a list of edible dishes for you.</p>
        </div>

    </div>
@endsection