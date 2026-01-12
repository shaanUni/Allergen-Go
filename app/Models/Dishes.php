<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dishes extends Model
{
    //
    protected $fillable = [
        'admin_id', 'dish_name', 'description', 'allergen_string', 'price', 'halal', 'vegan', 'vegetarian', 'no_allergens'
    ];

    //each dish belongs to an admin (restaurant)
    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function getFormattedAllergenStringAttribute(){
        
        if($this->allergen_string == ','){
            return 'No Allergens Added!';
        }

        $rawString = $this->allergen_string;

        //strip leading/trailing commas + whitespace
        $formattedString = preg_replace('/^\s*,+|,+\s*$/', '', $rawString);
        
        //set commas to: ", " (comma + single space)
        $formattedString = preg_replace('/\s*,\s*/', ', ', $formattedString);
        
        //Title-case each comma-separated item
        $formattedString = collect(explode(', ', $formattedString))
            ->map(fn ($item) => mb_convert_case(trim($item), MB_CASE_TITLE, 'UTF-8'))
            ->implode(', ');
        
        return $formattedString;
    }
}
