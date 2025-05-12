@extends('user.layout')

@section('content')
    @if(session('failure'))
        <div class="alert alert-danger">
            {{ session('failure') }}
        </div>
    @endif
    <div class="main">
        <h2 class="search-title">Search for dishes: </h2>
        
        <form method="POST" action="{{ route('user.searchCode') }}">
            @include('components.form')
        </form>

        <div class="info-box">
            <h2>Some information about AllergenGo: </h2>
        <p>If you are here, you must have allergies. The restaurant code you will enter will identify where you are eating. </p>
        <br>    
        <p>Once you add your allergies then submit alongside with the code, we will return a list of edible dishes for you.</p>
        </div>

    </div>
@endsection