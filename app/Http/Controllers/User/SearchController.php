<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Services\AllergenService;

class SearchController extends Controller
{

    //global variable which contains all allergens
    protected array $allergens;

    public function __construct()
    {
        //We have a file which the whole codebase uses to acsess allergens. This is the single source of truth for whole app/site, which will refer here.
        $this->allergens = config('allergens');
    }

    //
    public function search()
    {
        return view(
            'user.search',
            ['allergens' => $this->allergens]
        );
    }

    public function searchCode(Request $request)
    {
        //Validate code entered by user
        $validated = $request->validate([
            'restaurant_code' => 'required|string|max:20|alpha_dash',
            'allergens' => 'array',
        ]);

        //This will contian an array of all the users variables
        $userAllergies = $validated['allergens'];

        //store validated code in restaurantCode
        $restaurantCode = $validated['restaurant_code'];

        //Here we find the admin (restaurant) where the restaurant_code matches ours, and we eager load it with all of the dishes
        $restaurant = Admin::with('dishes')->where('restaurant_code', $restaurantCode)->first();

        //if the search found no matches
        if ($restaurant == null) {
            return redirect()->route('user.search')->with('failure', 'Code invalid. try again.');
        }

        //Get the dishes that where eager loaded
        $dishes = $restaurant->dishes;

        //This will contain dishes the user can eat
        $edibleDishes = [];

        //This is where the fun begins - Anakin Skywalker
        foreach ($dishes as $dish) {
            //Parse dish's allergens
            $dishAllergens = AllergenService::parse($dish->allergen_string)['allergens'];

            //Array intersect will return an array of values that are common in both arrays
            $commonAllergens = array_intersect($dishAllergens, $userAllergies);
            
            //If the array is empty, this means the dish does not have any allergens the user is allergic to
            if (empty($commonAllergens)) {
                $edibleDishes[] = $dish;
            }
        }


        //return to the view with the dish and restaurant
        return view(
            'user.list',
            ['dishes' => $edibleDishes],
            ['restaurant' => $restaurant],
        );
        //now we have the code, find the restaurant with that code, then find all dishes for that restaurant
        //dd($dishes);
    }
}
