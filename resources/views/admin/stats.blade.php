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
                <h2>Failed Searches - {{ $failedSearchCount }}</h2>
                <p class="stat-info">
                    These are user allergy combinations that didn’t return any edible dishes:
                </p>
                <div class="stat-list">
                    @foreach ($failedSearches as $failed)
                        @php
                            $failedAllergens = \App\Services\AllergenService::parse($failed->user_allergy_string)['allergens'];
                            $halal = $failed->halal == true ? 'halal,' : '';
                            $vegan = $failed->vegan == true ? 'vegan,' : '';
                            $vegetarian = $failed->vegetarian == true ? 'vegetarian,' : '';
                        @endphp
                                               
                            <p class="stat-item">  <strong>{{ $halal }} {{ $vegan }} {{ $vegetarian }}</strong> {{ implode(', ', $failedAllergens) }}</p>
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
                    <table class="dish-counts-table">
                        <thead>
                            <tr>
                                <th>Dish Name</th>
                                <th>Revenue</th>
                                <th>Times selected</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedByDishId as $dishId => $group)
                                @php
                                    $dish = \App\Models\Dishes::find($dishId);
                                    $revenue = $dish->price * count($group);
                                    $count = count($group);
                                @endphp
                                <tr>
                                    <td>{{ $dish->dish_name }}</td>
                                    <td>£{{ number_format($revenue, 2) }}</td>
                                    <td class="count">{{ $count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


            </div>

            <div class="stats-card">
                <h2>Dishes by allergen</h2>
                <p class="stat-info">
                    People with the allergy you entered, have selected the following dishes:
                </p>
                <form method="GET" action="{{ route('admin.stats') }}" class="mb-3 search-dishes-div">
                    <input class="form-control text-box" type="text" name="search_allergen" placeholder="search"
                        value="{{ request('search_allergen') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <p>Total Dishes selected by people with this allergy: {{ $filteredDishesCount }}</p>
                <ul class="allergen-list">
                    @foreach ($groupedCounts as $dishId => $count)
                        @php
                            $dish = \App\Models\Dishes::findOrFail($dishId);
                            $name = $dish->dish_name;
                        @endphp
                        <li>
                            <span class="allergen-name">{{ $name }}</span>
                            <span class="allergen-count">{{ $count }}</span>
                        </li>
                    @endforeach

                </ul>
            </div>

        </div>
    </div>
 
        <p>Below is the same form that the user would use, when visiting your restaurant. Use the form to replicate their experience, and see 
            if any certain allergies are lacking dishes on your menu.
        </p>

@endsection
