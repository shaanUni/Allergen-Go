<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    //one to many relationship with dishes (one restaurant can have many dishes)
    public function dishes(){
        return $this->hasMany(Dishes::class);
    }

    public function allergenCount(){
        return $this->hasMany(AllergenCount::class);
    }
}
