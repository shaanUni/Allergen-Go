<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedDishes extends Model
{
    protected $table = 'selected_dishes';
    
    //
    protected $fillable = [
        'admin_id', 'dishes_id', 'user_allergy_string',
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function dish(){
        return $this->belongsTo(Dishes::class);
    }
}
