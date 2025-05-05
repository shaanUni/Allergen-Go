<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dishes;
use App\Services\AllergenService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DishController extends Controller
{
    //global variable which contains all allergens
    protected array $allergens;

    public function __construct()
    {
        //We have a file which the whole codebase uses to acsess allergens. This is the single source of truth (dramatic aahhhh...).
        $this->allergens = config('allergens');
    }
    public function index()
    {
        //retrive all dishes belonging to the currently authenticated admin
        $dishes = Dishes::where('admin_id', Auth::guard('admin')->id())->get();
        return view('admin.dishes.index', compact('dishes'));
    }
    public function create()
    {
        return view('admin.dishes.create', ['allergens' => $this->allergens]);
    }

    public function store(Request $request)
    {
        //validate all inputs for creating a new dish
        $validated = $request->validate([
            'dish_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'allergens' => 'array',
            'price' => 'required|numeric|min:0',
        ]);

        $allergenString = AllergenService::restaurantSerialize($request);

        //ensure that we are only adding the dish to the correct restaurant
        $validated['admin_id'] = Auth::guard('admin')->id();
        $validated['allergen_string'] = $allergenString;

        Dishes::create($validated);

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

        return view('admin.dishes.edit', ['allergens' => $this->allergens, 'dish' => $dish, 'combined' => $combined, 'selectedAllergens' => $allergens]);
    }

    public function update(Request $request, $id)
    {
        //First find the dish
        $dish = Dishes::findOrFail($id);

        $this->authorizeDish($dish);

        //validate new inputs for new dish
        $validated = $request->validate([
            'dish_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'allergens' => 'array',
            'price' => 'required|numeric|min:0',
        ]);

        $allergenString = AllergenService::restaurantSerialize($request);

        //ensure that we are only adding the dish to the correct restaurant
        $validated['admin_id'] = Auth::guard('admin')->id();
        $validated['allergen_string'] = $allergenString;

        //ensure that we are only adding the dish to the correct restaurant
        $validated['admin_id'] = Auth::guard('admin')->id();

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
        if ($dish->admin_id != Auth::guard('admin')->id()) {
            abort(403, 'No');
        }
    }

}
