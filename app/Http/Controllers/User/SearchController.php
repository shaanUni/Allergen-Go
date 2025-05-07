<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\AllergenService;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


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
            'allergens' => 'required|array',
        ]);

        //This will contian an array of all the users allergies
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

        $dishesWithRemoveables = [];

        
        $continueBool = false;

        //Needed to check for duplicates in removeable array
        $existingIds = [];

        //This is where the fun begins - Anakin Skywalker
        foreach ($dishes as $dish) {
            //Parse dish's allergens
            $dishAllergens = AllergenService::parse($dish->allergen_string)['allergens'];

            //Here we will filter out removeable allergens, as the user can still eat it, will just need to ask chef to remove it
            $filteredDishAllergens = [];

            //Array of removeable allergens
            $removeableAllergens = AllergenService::parse($dish->allergen_string)['combined'];

            //Index to accsess allergens
            $i = 0;

            //Loop through removeable allergens
            foreach ($removeableAllergens as $removeable) {
                //If the allergen is not removeable, then it should remain in the filtered array which will be compared with the users allergens
                if ($removeable == false) {
                    $filteredDishAllergens[] = $dishAllergens[$i];
                //If it is removeable and the removeable allergen is in the users allergies
                } else if($removeable == true && in_array($dishAllergens[$i], $userAllergies)){
                    //If the dish has already been added to the array, skip the rest of the iteration
                    if (in_array($dish->id, $existingIds)) {
                        continue;
                    }
                
                    $dishesWithRemoveables[] = $dish;
                    $continueBool = true;

                    //We now know this dish has been added to the removeables array
                    $existingIds[] = $dish->id;

                    break;
                } 

                if(!empty(array_intersect($filteredDishAllergens, $userAllergies))){
                    $dishesWithRemoveables = [];
                    $continueBool = false;
                }
                $i++;
            }

            //This tells us the dish had a removeable dish that the user was allergic to, so no need to finish the loop on this iteration, as removeable dishes have their own array
            if($continueBool == true){
                $continueBool = false;
                continue;
            }
            
            //Array intersect will return an array of values that are common in both arrays
            $commonAllergens = array_intersect($filteredDishAllergens, $userAllergies);

            //If the array is empty, this means the dish does not have any allergens the user is allergic to
            if (empty($commonAllergens)) {
                $edibleDishes[] = $dish;
            }
        }

        //return to the view with the dish and restaurant
        return view(
            'user.list',
            [
                'dishes' => $edibleDishes,
                'removeables' => $dishesWithRemoveables,
                'restaurant' => $restaurant,
            ],
        );
    }
}
