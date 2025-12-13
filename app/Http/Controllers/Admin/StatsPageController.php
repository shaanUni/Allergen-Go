<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Models\Dishes;
use App\Models\Searches;
use App\Models\AllergenCount;
use App\Models\Opt_in_logs;
use App\Models\SelectedDishes;

use App\Services\AllergenService;
use App\Services\SearchService;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatsPageController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'search_allergen' => ['nullable', 'string', 'max:255']
        ]);

        //Get the data from the searches table
        $searches = self::baseSearchQuery()->get();

        //This can give the restaurant an insight into how many people will allergens go to the restaurant. It is just a total of all the searches.
        $totalSearches = count($searches);

        //Find all of the failed searches. (failed meaning the user could not have anything to eat)
        $failedSearches = self::baseSearchQuery()
            ->where('failure', true)
            ->orderBy('created_at', 'desc') //order by most recent
            ->take(5) // restrict at 5
            ->get();

        //Find the total of all the failed searches
        $failedSearchesCount = self::baseSearchQuery()
            ->where('failure', true)
            ->orderBy('created_at', 'desc') //order by most recent
            ->count();

        $allergenCounts = AllergenCount::where('admin_id', Auth::guard('admin')->id())
            ->orderBy('count', 'desc')
            ->get();

        //Get the restaurant code for the form
        $restaurant = Admin::find(Auth::guard('admin')->id());
        $restaurantCode = $restaurant->restaurant_code;

        $halalUsers = self::baseSearchQuery()
            ->where('halal', 1)
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

        do {
            $uuid = (string) Str::uuid();
            //The check for uniqueness
        } while (Opt_in_logs::where('session_uuid', $uuid)->exists());
          
        // Grab exsisting UUIDs or create an array
        $uuids = session('uuids', []);

        if (!in_array($uuid, $uuids)) {
            //add the new uuid
            $uuids[] = $uuid;
            session(['uuids' => $uuids]);
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
                'uuid' => $uuid, 
            ],
        );
    }

    //Get all the searches from the same admin (multi tenancy), and eager load with dishes
    function baseSearchQuery()
    {
        return Searches::with('admin.dishes')
            ->where('admin_id', Auth::guard('admin')->id());
    }

    public function search(Request $request)
    {

        
        //Call a service method that will compare user allergies with the dish allergens
        $filteredAllergens = SearchService::search($request, "client");
        
        //The search service will return false if the user added no data to the form or restaurant code, so redirect
        if ($filteredAllergens == "empty") {
            return redirect()->route('admin.stats')->with('failure', 'You must select either a dietary restriction (e.g. halal, vegan), or one allergen.');
        }
        
        //Gather results to pass through
        $edibleDishes = $filteredAllergens['dishes'];
        $dishesWithRemoveables = $filteredAllergens['removeables'];
        $restaurant = $filteredAllergens['restaurant'];
        
        //get the UUID
        $uuid = $request->input('uuid');
        
        //return to the view with the dish and restaurant
        return view(
            'admin.list',
            [
                'dishes' => $edibleDishes,
                'removeables' => $dishesWithRemoveables,
                'restaurant' => $restaurant,
                'uuid' => $uuid,
            ],
        );
    }

    public function showIndividualDish(Request $request, $id, $state)
    {
        //get the UUID
        $uuid = $request->input('uuid');

        //find the dish in the DB
        $dish = Dishes::findOrFail($id);

        //parse that dishes allergen info
        $allergens = AllergenService::parse($dish->allergen_string)['allergens'];
        $removeable = AllergenService::parse($dish->allergen_string)['combined'];

        return view('user.individual', ['dish' => $dish, 'state' => $state, 'uuid' => $uuid], compact('allergens', 'removeable'));
    }

}