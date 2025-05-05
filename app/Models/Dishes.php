<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dishes extends Model
{
    //
    protected $fillable = [
        'admin_id', 'dish_name', 'description', 'allergen_string', 'price'
    ];

    //each dish belongs to an admin (restaurant)
    public function admin(){
        return $this->belongsTo(Admin::class);
    }
}
