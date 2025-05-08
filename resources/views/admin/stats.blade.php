@extends('admin.layout')

@section('content')
    <h1>SHAAN</h1>
    <p>Total Number of searches: {{ $totalSearches }}</p>

    <p>Failed searches: </p>
    @foreach ($failedSearches as $failed)
        @php
            $failedAllergens = \App\Services\AllergenService::parse($failed->user_allergy_string)['allergens'];
        @endphp
        <p>{{ implode(', ', $failedAllergens) }}</p>
    @endforeach()
    <p>Allergen Counts:</p>
    @foreach ($allergenCounts as $counts)
        <p>{{ $counts->allergen }}</p>
        <p>{{ $counts->count }}</p>
    @endforeach

    <form method="POST" action="{{ route('admin.search') }}">
        @include('components.form')
    </form>

@endsection