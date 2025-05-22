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

        //Get the data from the searches table, and eager load it with the restaurant data and all it's dishes.
        $searches = Searches::with('admin.dishes')
            ->where('admin_id', Auth::guard('admin')->id()) // Only take their own searches
            ->get();

        //This can give the restaurant an insight into how many people will allergens go to the restaurant. It is just a total of all the searches.
        $totalSearches = count($searches);

        //Find all of the failed searches. (failed meaning the user could not have anything to eat)
        $failedSearches = Searches::with('admin.dishes')
            ->where('admin_id', Auth::guard('admin')->id())
            ->where('failure', true)
            ->orderBy('created_at', 'desc') //order by most recent
            ->take(5) // restrict at 5
            ->get();

        //Find the total of all the failed searches
        $failedSearchesCount = Searches::with('admin.dishes')
            ->where('admin_id', Auth::guard('admin')->id())
            ->where('failure', true)
            ->orderBy('created_at', 'desc') //order by most recent
            ->count();

        $allergenCounts = AllergenCount::where('admin_id', Auth::guard('admin')->id())
            ->orderBy('count', 'desc')
            ->get();

        //Get the restaurant code for the form
        $restaurant = Admin::find(Auth::guard('admin')->id());
        $restaurantCode = $restaurant->restaurant_code;

        $halalUsers = Searches::with('admin.dishes')
            ->where('admin_id', Auth::guard('admin')->id())
            ->where('halal', true)
            ->get();

        $totalHalalUsers = count($halalUsers);

        //List of allergens for the form
        $allergens = config('allergens');

        $allergenSearch = $request->input('search_allergen');

        $dishes = SelectedDishes::where('admin_id', Auth::guard('admin')->id())->get();
        $groupedByDishId = $dishes
            ->groupBy('dishes_id')                      // Group by dishes_id
            ->sortByDesc(fn($group) => $group->count()) // Sort groups by count descending
            ->take(7);

        $filteredDishes = [];
        $storeDishId = 0;

        foreach ($groupedByDishId as $dishId => $group) {
            $storeDishId = $dishId;
            foreach ($group as $selected) {
                if (str_contains($selected->user_allergy_string, $allergenSearch)) {
                    $filteredDishes[] = $storeDishId;
                    break;
                }
            }
        }

        $filteredDishes = Dishes::findMany($filteredDishes);

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