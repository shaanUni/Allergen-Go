<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpData extends Model
{
    //
    protected $fillable = [
        'admin_id', 'ip_address', 'switches', 'date_of_first_switch'
    ];
    
    protected $casts = [
        'date_of_first_switch' => 'date',
    ];

    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
