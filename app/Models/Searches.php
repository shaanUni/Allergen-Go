<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
Searches Table;

    This is here for the stats admin page. When the user uses a restaurants code, the key information will be added to this table
    The restaurant will then see all orders with their ID, and we can extract the information and present it visually.
*/

class Searches extends Model
{
    //
    protected $fillable = [
        'admin_id', 'user_allergy_string', 'failure', 'halal' 
    ];

    //Each record of a search will have the restaurant it belongs to
    public function admin(){
        return $this->belongsTo(Admin::class);
    }

}
