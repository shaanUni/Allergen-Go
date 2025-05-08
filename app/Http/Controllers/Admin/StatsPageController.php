<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Models\Dishes;
use App\Models\Searches;
use App\Models\AllergenCount;

use App\Services\AllergenService;
use App\Services\SearchService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatsPageController extends Controller
{

    public function index()
    {

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

        $allergenCounts = AllergenCount::where('admin_id', Auth::guard('admin')->id())
            ->orderBy('count', 'desc')
            ->get();

        //Get the restaurant code for the form
        $restaurant = Admin::find(Auth::guard('admin')->id());
        $restaurantCode = $restaurant->restaurant_code; 
        
        //List of allergens for the form
        $allergens = config('allergens');
        
        return view(
            'admin.stats',
            [
                'totalSearches' => $totalSearches,
                'failedSearches' => $failedSearches,
                'allergenCounts' => $allergenCounts,
                'allergens' => $allergens,
                'code' => $restaurantCode, // restaurant ID for the search form
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