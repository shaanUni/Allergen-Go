<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opt_in_logs extends Model
{
    protected $table = 'gdpr_logs';
    protected $fillable = [
        'session_uuid',
        'consent_given',
        'consent_version',
    ];

}
