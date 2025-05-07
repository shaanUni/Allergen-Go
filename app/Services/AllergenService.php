<?php

namespace App\Services;

use Illuminate\Http\Request;

//this service class can be called anywhere
class AllergenService
{
    //To turn allergen and removable array into a string. A seperate one is needed for the restaurant, as they add removable data, while the user will not, they only add allergens
    public static function restaurantSerialize(Request $request)
    {
        // Allergens, and wether it is removable will go in here
        $removableData = [];
        $allergenString = ",";

        //This merges the data together, for example: nuts => true, eggs => false, where the boolean represents wether the alergen can be removed from the food
        foreach ($request->input('allergens', []) as $allergen) {
            $removableData[$allergen] = isset($request->removables[$allergen]);
        }

        //we will convert and store allergens as a string, e.g. "*eggs,nuts,*milk,", which will be parsed
        foreach ($removableData as $allergen => $isRemovable) {

            //If it is removable, add an asterix * before the letter
            if ($isRemovable == true) {
                $allergenString .= "*"; //when adding string to a string, need to do .= rather than +=
            }
            //add the allergen itself
            $allergenString .= $allergen;

            // so parser knows this block if info is over
            $allergenString .= ",";
        }

        return $allergenString;
    }

    //To parse an allergen string, and turn back into arrays
    public static function parse($allergenString)
    {
        //This code now parses the allergen string
        $removedCheck = false;
        $removable = [];
        $allergens = [];
        $newAllergenString = "";

        //Start from 1, as we know string will always start with a comma
        for ($i = 1; $i < strlen($allergenString); $i++) {
            //if the character is not a asterix, ie removable, then add it to the allergen string being constructed
            if ($allergenString[$i] != "*") {
                $newAllergenString .= $allergenString[$i];
            }

            //Add true to the removable array
            if ($allergenString[$i] == "*") {
                $removable[] = true;
                //this is important, if this never turns true, then we will need to make the removeable "flag" false in the array
                $removedCheck = true;
            }

            //start of new "block"
            if ($allergenString[$i] == ",") {
                //new string into array
                $allergens[] = $newAllergenString;

                //reset for next loop
                $newAllergenString = "";

                //If it was not removable, then set it as false
                if ($removedCheck == false) {
                    $removable[] = false;
                }

                //reset for next loop
                $removedCheck = false;
            }
        }
        //trims out the commas
        $allergens = array_map('trim', $allergens);
        $allergens = array_map(fn($a) => rtrim($a, ','), $allergens);

        $combined = array_combine($allergens, $removable);

        return [
            'allergens' => $allergens,
            'combined' => $combined,
        ];

    }
}

/*
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

                }
                $i++;
            }
            
            //Array intersect will return an array of values that are common in both arrays
            $commonAllergens = array_intersect($filteredDishAllergens, $userAllergies);

            //If the array is empty, this means the dish does not have any allergens the user is allergic to
            if (empty($commonAllergens)) {
                $edibleDishes[] = $dish;
            }
        }

        $filteredEdibleDishes = [];
        $dishesWithRemoveables = [];

        //Filters dishes with removeable allergens
        for ($i = 0; $i < count($edibleDishes); $i++) {
            if (Str::contains($edibleDishes[$i]->allergen_string, "*")) {
                $dishesWithRemoveables[] = $edibleDishes[$i];
            } else {
                $filteredEdibleDishes[] = $edibleDishes[$i];
            }
        }
*
        //return to the view with the dish and restaurant
        return view(
            'user.list',
            [
                'dishes' => $filteredEdibleDishes,
                'removeables' => $dishesWithRemoveables,
                'restaurant' => $restaurant,
            ],
        );
    }
}

*/