<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SIPUser extends Model
{
    use HasFactory;
    
    /**
     * Setting up the tale as sip_users
     *
     * @var array
     */
    protected $table = 'sip_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',	
        'password',	
        'host_name',	
        'port',
        'country_code'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
