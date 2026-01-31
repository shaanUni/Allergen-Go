<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AllergenCount;
use App\Models\Dishes;
use App\Models\DishShare;
use App\Models\Searches;

use Illuminate\Http\Request;

//Restaurants will have their own dishes, then dishes from the parent in the dish share.
//this lil serevice will abstract getting ALL dishes
class GetAllDishesService
{
    public function getDishes(int $restaurant_id)
    {
        //Get the dishes that were eager loaded
        $ownDishes = Dishes::where('admin_id', $restaurant_id);

        $admin = Admin::find($restaurant_id);

        //if they have the super admin id set, then they have a parent org
        $partOfOrg = !is_null($admin->super_admin_id);

        //Retrive any dishes from "dish shares", where a parent restaurant would share its dishes
        $share = DishShare::where('child_admin_id', $restaurant_id)->where('status', true)->first();

        $dishes = $ownDishes;

        //if they have a parent restaurant as part of an orginisation
        if ($partOfOrg) {
            //Get the admin relationship with the parent
            $parentAdminId = $admin->super_admin_id;

            $parentAdmin = Admin::find($parentAdminId);

            //only share the dishes if the super admin opted into doing so
            if ($parentAdmin->share_dishes) {
                //Query the parent admins dishes 
                $sharedDishes = Dishes::where('admin_id', $parentAdminId);

                //merge the query builders
                $dishes = $ownDishes->union($sharedDishes);
            }
        //if they have parent restaurant from a dish share (org and dish share are not mutually exclusive)
        } else if ($share) {

            //Get the admin relationship with the parent
            $parentAdminId = $share->parentAdmin->id;

            //Query the parent admins dishes 
            $sharedDishes = Dishes::where('admin_id', $parentAdminId);

            //merge the query builders
            $dishes = $ownDishes->union($sharedDishes);

        }

        return $dishes;
    }

    public function mergeDishes($admin_id)
    {

    }
    public function dishShareStatus(int $admin_id)
    {
        $share = DishShare::where('child_admin_id', $admin_id)->where('status', true)->first();

        //If the query above is not null, the admin is involved in a dish share
        $dishShareStatus = $share != null ? true : false;

        return $dishShareStatus;
    }
}
