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

    public function qrCode($code)
    {
        return view('user.userqrcode', ['code' => $code, 'allergens' => $this->allergens]);
    }

    public function searchCode(Request $request)
    {

        //generate new uuid
        $uuid = (string) Str::uuid();

        // Grab exsisting UUIDs or create an array
        $uuids = session('uuids', []);

        if (!in_array($uuid, $uuids)) {
            //add the new uuid
            $uuids[] = $uuid;
            session(['uuids' => $uuids]);
        }
        //wipe everything, in case user has come back 
        session()->forget(['selectedDishes', 'selectedRemoveableDishes', 'removeables', 'user_allergy_string']);

        //Call a service method that will compare user allergies with the dish allergens
        $filteredAllergens = SearchService::search($request, "user");

        //The search service will return false if the user added no data to the form or restaurant code, so redirect
        if ($filteredAllergens == "empty") {
            return redirect()->route('user.qr', ['code' => $request['restaurant_code']])->with('failure', 'You must select either halal, or one allergen.');
        } else if ($filteredAllergens == "code") { // if the user entered an invalid code
            return redirect()->route('user.search')->with('failure', 'You must select a valid code.');
        }

        //Gather results to pass through
        $edibleDishes = $filteredAllergens['dishes'];
        $dishesWithRemoveables = $filteredAllergens['removeables'];
        $restaurant = $filteredAllergens['restaurant'];

        //Give the user a unique ID
        if (!session()->has('guest_token')) {
            session(['guest_token' => (string) Str::uuid()]);
        }

        //Store the restaurant id with the user: note - should ! be removed as needs to be updated
        if (!session()->has('restaurant')) {
            session(['restaurant' => $restaurant]);
        }

        if (!session()->has('removeables'.$uuid)) {
            session(['removeables'.$uuid => $dishesWithRemoveables]);
        }

        if (!session()->has('dishes'. $uuid)) {
            session(['dishes'. $uuid => $edibleDishes]);
        }



        dump($uuid);

        //return to the view with the dish and restaurant
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

    public function showIndividualDish(Request $request, $id, $state)
    {
        $uuid = $request->input('uuid');

        $dish = Dishes::findOrFail($id);

        $allergens = AllergenService::parse($dish->allergen_string)['allergens'];
        $removeable = AllergenService::parse($dish->allergen_string)['combined'];

        return view('user.individual', ['dish' => $dish, 'state' => $state, 'uuid' => $uuid], compact('allergens', 'removeable'));
    }


}
