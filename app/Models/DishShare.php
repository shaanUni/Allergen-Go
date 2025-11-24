<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
    an admin may want to share their dishes with another restaurant, if they
    did not purchase the bulk deal and have many locations
*/

class DishShare extends Model
{
    //
    protected $fillable = [
        'parent_admin_id', 'child_admin_id', 'status', 'uuid'
    ];

    protected $table = 'dish_share';

    public function parentAdmin(){
        return $this->belongsTo(Admin::class, 'parent_admin_id');
    }

    public function childAdmin(){
        return $this->belongsTo(Admin::class, 'child_admin_id');
    }
}
