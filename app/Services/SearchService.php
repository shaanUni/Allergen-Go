<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Searches;
use App\Models\AllergenCount;

use Illuminate\Http\Request;

//this service class can be called anywhere
class SearchService
{
    //Take request from user, and compare dish allergens with user allergies
    public static function search(Request $request, $whoami)
    {
        //Validate code entered by user
        $validated = $request->validate([
            'restaurant_code' => 'required|string|max:20|alpha_dash',
            'allergens' => 'array',
            'halal' => 'boolean'
        ]);

        //Here we find the admin (restaurant) where the restaurant_code matches ours, and we eager load it with all of the dishes
        $restaurant = Admin::with('dishes')->where('restaurant_code', $validated['restaurant_code'])->first();

        //if the search found no matches
        if ($restaurant == null) {
            return "code";
        }

        //If the user entered the form with no info
        if (isset($validated['allergens']) == 0 && $validated['halal'] == 0) {
            return "empty";
        }

        $halal = $validated['halal'];

        $userAllergies = [];

        //In case they don't have an allergy, they just want to search for halal dishes
        if (isset($validated['allergens'])) {
            //This will contian an array of all the users allergies
            $userAllergies = $validated['allergens'];
        }

        //store validated code in restaurantCode
        $restaurantCode = $validated['restaurant_code'];

        //Compare user allergies with restaurant dishes allergens
        $filteredAllergens = self::filterAllergens($userAllergies, $halal, $restaurant, $whoami);

        //If the restaurant code was invalid, redirect
        if (!$filteredAllergens && $whoami == "user") {
            return false;
        } else if (!$filteredAllergens && $whoami == "client") {
            return false;
        }

        $edibleDishes = $filteredAllergens['dishes'];
        $dishesWithRemoveables = $filteredAllergens['removeables'];
        $restaurant = $filteredAllergens['restaurant'];

        return [
            'dishes' => $edibleDishes,
            'removeables' => $dishesWithRemoveables,
            'restaurant' => $restaurant,
        ];
    }

    //Main function that compares user allergies with dish allergens
    public static function filterAllergens($userAllergies, $halal, $restaurant, $whoami)
    {


        //Get the dishes that where eager loaded
        $dishes = $restaurant->dishes;

        //This will contain dishes the user can eat
        $edibleDishes = [];
        $dishesWithRemoveables = [];

        //Needed to check for duplicates in removeable array
        $existingIds = [];

        $continueBool = false;

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
                } else if ($removeable == true && in_array($dishAllergens[$i], $userAllergies) && self::isHalal($dish->halal, $halal)) {
                    //If the dish has already been added to the array, skip the rest of the iteration
                    if (in_array($dish->id, $existingIds)) {
                        $i++;
                        continue;
                    }

                    $dishesWithRemoveables[] = $dish;
                    $continueBool = true;

                    //We now know this dish has been added to the removeables array
                    $existingIds[] = $dish->id;
                }

                //Even if there is 1 removeable allergen, there may be another non removeable one that the user is allergic to.  
                if (!empty(array_intersect($filteredDishAllergens, $userAllergies)) && $continueBool == true) { //In the non removeable array, is there any allergens the user can't have
                    //if so, splice the last element added from the removeable array
                    $lastItem = array_pop($dishesWithRemoveables);
                    $continueBool = false;
                }

                $i++;
            }

            //This tells us the dish had a removeable dish that the user was allergic to, so no need to finish the loop on this iteration, as removeable dishes have their own array
            if ($continueBool == true) {
                $continueBool = false;
                continue;
            }

            //Array intersect will return an array of values that are common in both arrays
            $commonAllergens = array_intersect($filteredDishAllergens, $userAllergies);

            //If the array is empty, this means the dish does not have any allergens the user is allergic to, also ensure that the food is halal if user needs that
            if (empty($commonAllergens) && self::isHalal($dish->halal, $halal)) {
                $edibleDishes[] = $dish; // edibleDishes += dish
            }
        }

        //We only want to do all of the logging stuff if it is a user doing the search, not the client themselves
        if ($whoami == "user") {
            //Restaurant ID for Searches table
            $restaurantID = $restaurant->id;

            //The allergen count table stores each allergen for a restaurant, and how many users have had that allergy, or more accurateley, how many searches have contained that allergy
            foreach ($userAllergies as $allergy) {
                //First or new will first check to see if it exsits, if not, create a new row
                $allergenCount = AllergenCount::firstOrNew([
                    'admin_id' => $restaurantID,
                    'allergen' => $allergy,
                ]);

                //Incremennt count
                $allergenCount->count = $allergenCount->count + 1;
                $allergenCount->save();
            }

            //Convert User allergies array into a string
            $userAllergiesString = AllergenService::userSerialize($userAllergies);

            //Boolean that will go into the DB, to give the status on the search: true => dishes were returned, false => no dishes were returned, the user could not eat any food.
            $searchFailureStatus = false;

            //If there are no dishes in the edible dishes, or the removeables, the user can have nothing from this restaurant.
            if (count($edibleDishes) == 0 && count($dishesWithRemoveables) == 0) {
                $searchFailureStatus = true;
            }

            //Save this search in table
            Searches::create([
                'admin_id' => $restaurantID,
                'user_allergy_string' => $userAllergiesString,
                'failure' => $searchFailureStatus,
                'halal' => $halal,
            ]);
        }

        return [
            'dishes' => $edibleDishes,
            'removeables' => $dishesWithRemoveables,
            'restaurant' => $restaurant,
        ];
    }

    //behaviour for dealing with halal dishes
    public static function isHalal($dishHalal, $userHalal)
    {
        if ($userHalal == true && $dishHalal == true) {
            return true;
        } else if ($userHalal == false) {
            return true;
        }
        return false;
    }
}
