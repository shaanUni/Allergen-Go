@extends('admin.layout')

@section('content')


    <div class="stats-page">
        <h1 class="stats-title">AllergenGo Stats Overview</h1>
        <p class="stats-total">Total Number of Searches: <span>{{ $totalSearches }}</span></p>
        <p class="stats-total">Total Number of Searches by halal users: <span>{{ $totalHalalUsers }}</span></p>

        <div class="stats-grid">
            <!-- Failed Searches Box -->
            <div class="stats-card">
                <h2>Failed Searches - {{$failedSearchCount }}</h2>
                <p class="stat-info">
                    These are user allergy combinations that didn’t return any edible dishes:
                </p>
                <div class="stat-list">
                    @foreach ($failedSearches as $failed)
                        @php
                            $failedAllergens = \App\Services\AllergenService::parse($failed->user_allergy_string)['allergens'];
                        @endphp
                        <p class="stat-item">{{ implode(', ', $failedAllergens) }}</p>
                    @endforeach
                </div>
            </div>

            <!-- Allergen Counts Box -->
            <div class="stats-card">
                <h2>Allergen Counts</h2>
                <p class="stat-info">
                    Number of times each allergen was flagged across all users:
                </p>
                <ul class="allergen-list">
                    @foreach ($allergenCounts as $counts)
                        <li>
                            <span class="allergen-name">{{ ucfirst($counts->allergen) }}</span>
                            <span class="allergen-count">{{ $counts->count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="search-page">

        <form method="POST" action="{{ route('admin.search') }}">
            @include('components.form')
        </form>
        </div>

@endsection