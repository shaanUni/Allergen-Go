<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Models\Dishes;
use App\Models\Searches;
use App\Models\AllergenCount;

use App\Models\SelectedDishes;
use App\Services\AllergenService;
use App\Services\SearchService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatsPageController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'search_allergen' => ['nullable', 'string', 'max:255']
        ]);

        //This variable will be used alot, it is a query getting all the searches and dishes for a restaurant
        $searchQuery = Searches::with('admin.dishes') // eager loading dishes
        ->where('admin_id', Auth::guard('admin')->id()); // security, ensure the restaurant can only see its own data

        //Get the data from the searches table
        $searches = $searchQuery->get(); 

        //This can give the restaurant an insight into how many people will allergens go to the restaurant. It is just a total of all the searches.
        $totalSearches = count($searches);

        //Find all of the failed searches. (failed meaning the user could not have anything to eat)
        $failedSearches = $searchQuery
            ->where('failure', true)
            ->orderBy('created_at', 'desc') //order by most recent
            ->take(5) // restrict at 5
            ->get();

        //Find the total of all the failed searches
        $failedSearchesCount = $searchQuery
            ->where('failure', true)
            ->orderBy('created_at', 'desc') //order by most recent
            ->count();

        $allergenCounts = AllergenCount::where('admin_id', Auth::guard('admin')->id())
            ->orderBy('count', 'desc')
            ->get();

        //Get the restaurant code for the form
        $restaurant = Admin::find(Auth::guard('admin')->id());
        $restaurantCode = $restaurant->restaurant_code;

        $halalUsers = $searchQuery
            ->where('halal', true)
            ->get();

        $totalHalalUsers = count($halalUsers);

        //List of allergens for the form
        $allergens = config('allergens');
        $dietaryRestrictions = config('dietary-restrictions');


        //Restaurant will enter an allergy, to sort all selected dishes where people have had this allergy
        $allergenSearch = $request->input('search_allergen');

        //Eager load dishes with the selected dishes table, and multi tenancy security
        $dishes = SelectedDishes::with('dish')->where('admin_id', Auth::guard('admin')->id())->get();

        //For the dish counts box
        $groupedByDishId = $dishes
            ->groupBy('dishes_id')                      // Group by dishes_id
            ->sortByDesc(fn($group) => $group->count()) // Sort groups by count descending
            ->take(7);


        //Another variable of eager laoded dishes with the selected dishes table
        $selectedDishes = SelectedDishes::with('dish')
            ->where('admin_id', Auth::guard('admin')->id())
            ->get();


        $filteredDishesCount = 0;
        $filteredDishes = [];
        $groupedCounts = [];

        //If restaurant entered something into search box
        if ($allergenSearch) {
            //Do the search
            $filtered = $selectedDishes->filter(function ($selected) use ($allergenSearch) {
                return $selected->dish && str_contains($selected->user_allergy_string, $allergenSearch);
            });

            //Get all the ids of selected dishes table
            $dishes1 = $filtered->unique('id')->filter();
            //then get ids of the dishes themselves
            $ids = $dishes1->pluck('dishes_id');
            //count each dish
            $groupedCounts = $ids->countBy()->sortDesc();
            //total
            $filteredDishesCount = count($ids);
            //Get dishes from ids
            $filteredDishes = Dishes::findMany($ids);
        }

        
        return view(
            'admin.stats',
            [
                'totalSearches' => $totalSearches,
                'failedSearches' => $failedSearches,
                'allergenCounts' => $allergenCounts,
                'allergens' => $allergens,
                'code' => $restaurantCode, // restaurant ID for the search form
                'failedSearchCount' => $failedSearchesCount,
                'totalHalalUsers' => $totalHalalUsers,
                'groupedByDishId' => $groupedByDishId,
                'filteredDishes' => $filteredDishes,
                'filteredDishesCount' => $filteredDishesCount,
                'groupedCounts' => $groupedCounts,
                'diet' => $dietaryRestrictions,
            ], 
        );
    }

    public function search(Request $request)
    {
        //Call a service method that will compare user allergies with the dish allergens
        $filteredAllergens = SearchService::search($request, "client");

        //Gather results to pass through
        $edibleDishes = $filteredAllergens['dishes'];
        $dishesWithRemoveables = $filteredAllergens['removeables'];
        $restaurant = $filteredAllergens['restaurant'];

        //return to the view with the dish and restaurant
        return view(
            'admin.list',
            [
                'dishes' => $edibleDishes,
                'removeables' => $dishesWithRemoveables,
                'restaurant' => $restaurant,
            ],
        );
    }

}