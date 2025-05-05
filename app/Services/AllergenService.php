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