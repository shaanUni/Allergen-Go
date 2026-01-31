<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DishRequest;

use App\Models\Admin;
use App\Models\Dishes;
use App\Models\DishShare;

use App\Services\AllergenService;

use App\Services\GetAllDishesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

class DishController extends Controller
{
    //global variable which contains all allergens
    protected array $allergens;

    //vegan, vegetarian, halal
    protected array $diet;

    protected int $admin_id;

    public function __construct()
    {
        //We have a file which the whole codebase uses to acsess allergens. This is the single source of truth for whole app/site, which will refer here.
        $this->allergens = config('allergens');
        $this->diet = config('dietary-restrictions');
        $this->admin_id = Auth::guard('admin')->id();
    }

    public function index(Request $request, GetAllDishesService $getDishesService, $admin_id = null)
    {

        //if this is being accesed from the super admin page
        if($admin_id){
            //set the admin id to whichever sub account they want to access
            $this->admin_id = $admin_id;
        }

        $request->validate([
            'search_dish' => ['nullable', 'string', 'max:255']
        ]);

        $dishes = $getDishesService->getDishes($this->admin_id);
        $dishShareStatus = $getDishesService->dishShareStatus($this->admin_id);
        
        //If admin used searchbar to search for dish by name or description
        if ($request->filled('search_dish')) {
            $search = $request->input('search_dish');
            //query
            $dishes->where(function ($q) use ($search) {
                $q->where('dish_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        //paginate dishes, including the query we made if there is one
        $dishes = $dishes->paginate(10);

        //Does this admin share it's dishes with anyone
        $children = DishShare::where('parent_admin_id', $this->admin_id)->where('status', true)->get();

        return view('admin.dishes.index', compact('dishes', 'dishShareStatus', 'children', 'admin_id'));
    }
    public function create()
    {
        return view('admin.dishes.create', ['allergens' => $this->allergens, 'diet' => $this->diet]);
    }

    public function store(DishRequest $request)
    {
        $validated = $request->validated();
        
        $allergenString = AllergenService::restaurantSerialize($request);

        //ensure that we are only adding the dish to the correct restaurant
        $validated['admin_id'] = $this->admin_id;
        $validated['allergen_string'] = $allergenString;

        $dish = Dishes::create($validated);

        if(isset($validated['no_allergens'])){
            $dish->no_allergens = true;
        }

        //This->diet contains dietary restrictions, halal, vagan.. etc
        foreach ($this->diet as $key) {
            if ($validated['diet'][$key] == 'true') { // Use the key to check the value from the form
                $dish->{$key} = true; //the key can even be used to access the model, as it is $dish->halal, $dish->vegan
            }
        }

        $dish->save();

        return redirect()->route('admin.dishes.index')->with('success', 'Dish created successfully.');
    }

    public function edit($id)
    {
        //First find the dish
        $dish = Dishes::findOrFail($id);

        $this->authorizeDish($dish);

        $result = AllergenService::parse($dish->allergen_string);
        $combined = $result['combined'];
        $allergens = $result['allergens'];

        $dietaryRestrictionSelectedArray = [];

        //If they are true in the DB, append it to the array, so the front end knows to keep it checked
        $dish->vegetarian ? $dietaryRestrictionSelectedArray[] = true : $dietaryRestrictionSelectedArray[] = false;
        $dish->vegan ? $dietaryRestrictionSelectedArray[] = true : $dietaryRestrictionSelectedArray[] = false;
        $dish->halal ? $dietaryRestrictionSelectedArray[] = true : $dietaryRestrictionSelectedArray[] = false;

        return view('admin.dishes.edit', ['allergens' => $this->allergens, 'dish' => $dish, 'combined' => $combined, 'selectedAllergens' => $allergens, 'diet' => $this->diet, 'dietSelected' => $dietaryRestrictionSelectedArray, 'no_allergens' => $dish->no_allergens]);
    }

    public function update(DishRequest $request, $id)
    {
        //First find the dish
        $dish = Dishes::findOrFail($id);

        $this->authorizeDish($dish);

        $validated = $request->validated();

        if(empty($validated['no_allergens'])){
            $dish->no_allergens = false;
            $dish->save();
        }

        $allergenString = AllergenService::restaurantSerialize($request);

        //ensure that we are only adding the dish to the correct restaurant
        $validated['admin_id'] = $this->admin_id;
        $validated['allergen_string'] = $allergenString;

        //This->diet contains dietary restrictions, halal, vagan.. etc
        foreach ($this->diet as $key) {
            if ($validated['diet'][$key] == 'true') { // Use the key to check the value from the form
                $dish->{$key} = true; //the key can even be used to access the model, as it is $dish->halal, $dish->vegan
            } else if ($validated['diet'][$key] == 'false') {
                $dish->{$key} = false; 
            }
        }

        $dish->save();
        $dish->update($validated);

        return redirect()->route('admin.dishes.index')->with('success', 'Dish updated successfully.');
    }

    public function destroy($id)
    {
        //First find the dish
        $dish = Dishes::findOrFail($id);

        $this->authorizeDish($dish);

        $dish->delete();

        // Redirect with success message
        return redirect()->route('admin.dishes.index')->with('success', 'Dish deleted successfully.');
    }

    //This function can be used when editing/deleting dishes, and ensures the dish being modified belongs to the current logged in user
    public function authorizeDish(Dishes $dish)
    {
        if ($dish->admin_id != $this->admin_id) {
            abort(403, 'No');
        }
    }

}
