<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Dishes;
use App\Models\Opt_in_logs;
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

    //vegan, vegetarian, halal
    protected array $diet;


    public function __construct()
    {
        //We have a file which the whole codebase uses to acsess allergens. This is the single source of truth for whole app/site, which will refer here.
        $this->allergens = config('allergens');
        $this->diet = config('dietary-restrictions');

    }

    //
    public function search()
    {
        return view(
            'user.search',
            ['allergens' => $this->allergens, 'diet' => $this->diet]
        );
    }

    public function qrCode($code)
    {
        return view('user.userqrcode', ['code' => $code, 'allergens' => $this->allergens, 'diet' => $this->diet]);
    }

    public function searchCode(Request $request)
    {

        do {
            $uuid = (string) Str::uuid();
            //The check for uniqueness
        } while (Opt_in_logs::where('session_uuid', $uuid)->exists());


        //Get the value of the opt in value
        $opt_in = $request->validate(['opt-in' => 'boolean']);
        $opt_in_value = $opt_in['opt-in'];
        
        Opt_in_logs::create([
            'session_uuid' => $uuid,
            'consent_given' => $opt_in_value,
            'consent_version' => 'v1.0',
        ]);
        
        //so we know if has been selected, also used in search service
        session(['opt-in' => $opt_in_value]);
        
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
            return redirect()->route('user.qr', ['code' => $request['restaurant_code']])->with('failure', 'You must select either a dietary restriction (e.g. halal, vegan), or one allergen.');
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

        //Store dishes with removeable allergens in session, with the key removeable plus whatever the uuid is, so it is specific to each person
        if (!session()->has('removeables'.$uuid)) {
            session(['removeables'.$uuid => $dishesWithRemoveables]);
        }

        //same with normal dishes as we did above. Now will look like dishes903020-12sas... We will pass the UUID through to the view, and from there will be used everywhere else in the app
        if (!session()->has('dishes'. $uuid)) {
            session(['dishes'. $uuid => $edibleDishes]);
        }

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

    //When User wants to view a specific dish
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
