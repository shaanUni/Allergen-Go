<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Dishes;
use App\Models\Searches;
use App\Models\AllergenCount;

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

    public function add($id, $state)
    {

        if (!session()->has('selectedDishes')) {
            session(['selectedDishes' => []]);
        }
        
        if (!session()->has('selectedRemoveableDishes')) {
            session(['selectedRemoveableDishes' => []]);
        }

        //store selected dishes array in local variable
        $selected = session('selectedDishes', []);
        $removeable = session('selectedRemoveableDishes', []);

        if($state == 1){
            if (!in_array($id, $selected)) {
                $selected[] = $id;
                session(['selectedDishes' => $selected]);
            }else{
                $modifiedArray = session('selectedDishes');
                $arrayKey = array_search($id, $modifiedArray);
                unset($modifiedArray[$arrayKey]); // remove
                $modifiedArray = array_values($modifiedArray); // reindex
                session(['selectedDishes' => $modifiedArray]); // place back in session
            }
        } else{
            if(!in_array($id, $removeable)){
                $removeable[] = $id;
                session(['selectedRemoveableDishes' => $removeable]);
            } else{
                $modifiedArray = session('selectedRemoveableDishes');
                unset($modifiedArray[$id]); // remove
                $modifiedArray = array_values($modifiedArray); // reindex
                session(['selectedDishes' => $modifiedArray]); // place back in session
            }
        }
   
        //reload the list page with the details in memory
        $edibleDishes = session('dishes');
        $dishesWithRemoveables = session('removeables');
        $restaurant = session('restaurant');

        return view(
            'user.list',
            [
                'dishes' => $edibleDishes,
                'removeables' => $dishesWithRemoveables,
                'restaurant' => $restaurant,
            ],
        );
    }

    public function selected()
    {

        $selectedDishes = Dishes::findMany(session("selectedDishes"));
        $removeable = Dishes::findMany(session("selectedRemoveableDishes"));

        $dishesWithRemoveables = session('removeables');
        $restaurant = session('restaurant');

        //remove everything from users session
        session()->forget(['selectedDishes', 'selectedRemoveableDishes', 'removeables', 'restaurant', 'dishes']);

        return view(
            'user.list',
            [
                'dishes' => $selectedDishes,
                'removeables' => $removeable,
                'restaurant' => $restaurant,
            ],
        );
    }



}
