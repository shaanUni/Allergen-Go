<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllergenCount extends Model
{
    protected $table = 'allergen_count';
    
    //
    protected $fillable = [
        'admin_id', 'allergen', 'count',
    ];

    //each dish belongs to an admin (restaurant)
    public function admin(){
        return $this->belongsTo(Admin::class);
    }
}
