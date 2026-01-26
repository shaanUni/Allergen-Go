<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $fillable = [
        'admin_id', 'city', 'street', 'postcode'
    ];
    
    protected $casts = [
        'date_of_first_switch' => 'date',
    ];

    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
