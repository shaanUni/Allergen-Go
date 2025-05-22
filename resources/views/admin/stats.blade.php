@extends('admin.layout')

@section('content')

<form action="{{ route('admin.dashboard') }}" method="get" style="display:inline;">
  <button type="submit" class="back-button">Back to Dashboard</button>
</form>


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
                        @if ($failed->halal == 1)
                            <p class="stat-item">User wanted a halal dish, and the allergens where -
                                {{ implode(', ', $failedAllergens) }}</p>
                        @else
                            <p class="stat-item">{{ implode(', ', $failedAllergens) }}</p>
                        @endif
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

            <div class="stats-card">
                <h2>Dish Counts</h2>
                <p class="stat-info">
                    Number of times each dish was selected:
                </p>
                <ul class="allergen-list">
                    @foreach($groupedByDishId as $dishId => $group)
                    @php
                    $dish = \App\Models\Dishes::findOrFail($dishId);
                    @endphp
                    <li>
                            <span class="allergen-name">{{ $dish->dish_name }}</span>
                            <span class="allergen-count">{{ count($group) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="stats-card">
                <h2>Dishes by allergen</h2>
                <p class="stat-info">
                    People with the allergy you entered, have selected the following dishes:
                </p>
                <form method="GET" action="{{ route('admin.stats') }}" class="mb-3 search-dishes-div">
        <input class="form-control text-box" type="text" name="search_allergen" placeholder="search" value="{{ request('search_allergen') }}">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
                <ul class="allergen-list">
                    @foreach ($filteredDishes as $dish)
                    <li>
                    <span class="allergen-name">{{ $dish->dish_name }}</span><br>
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