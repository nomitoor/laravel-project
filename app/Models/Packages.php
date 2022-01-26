<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    use HasFactory;
    
    /**
     * Setting up the primary key as    package_id
     *
     * @var array
     */
    protected $primaryKey = 'package_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'package_name',
        'price',
        'package_type',
        'stripe_package_id',
        'call_minutes',
        'call_country',
        'call_country_code',
        'allowed_calling_country',
        'excluded_calling_country'
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
