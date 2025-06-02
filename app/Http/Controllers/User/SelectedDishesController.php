<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Dishes;
use App\Models\Searches;
use App\Models\AllergenCount;

use App\Models\SelectedDishes;
use App\Services\AllergenService;
use App\Services\SearchService;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SelectedDishesController extends Controller
{

    //global variable which contains all allergens
    protected array $allergens;

    public function __construct()
    {
        //We have a file which the whole codebase uses to acsess allergens. This is the single source of truth for whole app/site, which will refer here.
        $this->allergens = config('allergens');
    }

    //This method boths adds and removes selected dishes
    public function add(Request $request, $id, $state)
    {
        //Get UUID from the view
        $uuid = $request->input('uuid');

        //If normal dishes
        if ($state == "1") {
            $selected = self::selectedDishHandler('selectedDishes'. $uuid, $id);
        } else { // if dishes with removeable allergens
            $selectedRemoveable = self::selectedDishHandler('selectedRemoveableDishes'. $uuid, $id);
        }

        //reload the list page with the details in memory
        $edibleDishes = session('dishes'.$uuid); //the session key is dishes + the uuid 
        $dishesWithRemoveables = session('removeables'.$uuid);
        $restaurant = session('restaurant');
        
        
        return view(
            'user.list',
            [
                'dishes' => $edibleDishes,
                'removeables' => $dishesWithRemoveables,
                'restaurant' => $restaurant,
                'uuid' => $uuid,
            ],
        );
    }

    //Render final page of users choices
    public function selected(Request $request)
    {
        $uuid = $request->input('uuid');

        //convert the array if ids into a collection of dishes
        $selectedDishes = Dishes::findMany(session('selectedDishes'. $uuid));
        $removeable = Dishes::findMany(session('selectedRemoveableDishes'. $uuid));
        
        //Merge into one collection and reidnex
        $allDishes = $selectedDishes->merge($removeable)->values();

        //restaurant info from session
        $restaurant = session('restaurant');
        $userAllergiesString = session('user_allergy_string');
        
        //Add info about the dishes that have been selected, for admins stats page
        foreach($allDishes as $dish){
            SelectedDishes::Create([
                'admin_id' => $restaurant->id,
                'dishes_id' => $dish->id,
                'user_allergy_string' => $userAllergiesString,
            ]);
        }

        return view(
            'user.list',
            [
                'dishes' => $selectedDishes,
                'removeables' => $removeable,
                'restaurant' => $restaurant,
                'uuid' => $uuid,
            ],
        );
    }

    //Logic for creating an array in the session, and removing and adding dishes to it
    public function selectedDishHandler($key, $id)
    {
        //If not yet created, then create the session with whatever key was given
        if (!session()->has($key)) {
            //init as an array
            session([$key => []]);
        }

        //store selected dishes array in local variable
        $selectedDishes = session($key, []);

        //If it does not exsist in the array, we are adding it
        if (!in_array($id, $selectedDishes)) {
            $selectedDishes[] = $id;
            session([$key => $selectedDishes]);
            //else we are removing it from the array
        } else {
            $arrayKey = array_search($id, $selectedDishes); // find the index of the dish id
            unset($selectedDishes[$arrayKey]); // use it to remove it from the array
            $selectedDishes = array_values($selectedDishes); // reindex for a clean array
            session([$key => $selectedDishes]); // place back in session
        }

    }

    //For when the user wants to re-select dishes
    public function reset(Request $request){
        
        $uuid = $request->input('uuid');

        $edibleDishes = session('dishes'.$uuid);
        $dishesWithRemoveables = session('removeables'.$uuid);
        $restaurant = session('restaurant');

        return view(
            'user.list',
            [
                'dishes' => $edibleDishes,
                'removeables' => $dishesWithRemoveables,
                'restaurant' => $restaurant,
                'uuid' => $uuid,
            ],
        );
    }

}
